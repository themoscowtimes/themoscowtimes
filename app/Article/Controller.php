<?php

namespace Article;

use Sulfur\View;
use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Cache;
use Url;

use Event\Model as EventModel;
use Tag\Model as TagModel;

class Controller
{

	public function preview($hash = null, Request $request, Response $response, Url $url, Model $model, View $view, Cache $cache)
	{

		if($hash !== null) {
			if($article = $cache->get('article_' . $hash)) {
				return $article;
			} else {
				return 'Preview no longer available';
			}
		}

		if($item = $model->preview($request->post())) {

			if($item->type == 'live') {
				$article = $view->render('article/live', [
					'item' => $item,
					'related' => $model->related($item, 4),
				]);
			} else {
				$nextItem = $model->next($item->time_publication);
				$article = $view->render('article/item', [
					'item' => $item,
					'next_item_id' => $nextItem,
					'related' => $model->related($item, 4),
				]);
			}

			$hash = md5(random_bytes(30));

			$cache->set('article_' . $hash, $article, 5 * 24 * 3600);

			if($cached = $cache->get('article_' . $hash)) {
				// check if it worked: if so, redirect
				$response->redirect($url->route('articlepreview', ['hash' => $hash]));
			} else {
				// if not: at least show the preview
				return $article;
			}
		}
	}


	public function view($slug, Model $model, View $view, Response $response, Url $url,  Cache $cache)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		if($item = $model->slug($slug)){
			$canonical = $url->route('article', $item->data());


			if($canonical != $url->current()) {
				$response->redirect($canonical);
			}

			$model->viewed($item->id);
			$key = 'article_' . $item->id;
			if($html = $cache->get($key)) {

			} else {
				// get full item
				$item = $model->one($item->id);
				if($item->type == 'live') {
					$html = $view->render('article/live', [
						'item' => $item,
						'related' => $model->related($item, 4),
					]);
				} else {
					$nextItem = $model->next($item->time_publication);
					$html = $view->render('article/item', [
						'item' => $item,
						'next_item_id' => $nextItem,
						'related' => $model->related($item, 4),
					]);
				}
				$cache->set($key, $html, 10 * 60);
			}
			return $html;
		} else {
			return $view->render('page/404');
		}
	}
	/*
	public function articlepdf(
		$slug,
		Model $model,
		View $view,
		Response $response,
		Cache $cache
	)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		if ($item = $model->slug($slug)) {
			$key = 'article_' . $item->id;
			$html = $view->render('article/item_pdf', [
				'item' => $model->one($item->id)
			]);
			$cache->set($key, $html, 10 * 60);
			return $html;

		} else {
			return $view->render('page/404');
		}
	}
	*/

	public function section($section, $offset = null, Model $model, EventModel $eventModel, View $view,  Cache $cache, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$key = 'section_' . $section . '_' . $offset;
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render($offset !== null ? 'article/more' : 'article/index', [
				'section' => $section,
				'tag' => null,
				'items' => $model->section($section,  $offset !== null ? 18 : 19, $offset),
				'events' => $offset !== null ? [] : ($section == 'city' ? $eventModel->all(5) : []),
			]);
			$cache->set($key, $html, 5 * 60);
			return $html;
		}
	}

  // Pass a new item to infinite scroll
	public function infiniteScrollItem($id = null, Model $model, View $view, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$item = $model->one($id);
		$nextItem = $model->next($item->time_publication);
		if (false && isset($_GET["amp"]) == 1) {
			$html = $view->render('article/item_amp', [
				'item' => $item,
				'next_item_id' => $nextItem,
				'related' => $model->related($item, 4)
			]);
	} else {
			$html = $view->render('article/item_raw', [
				'item' => $item,
				'next_item_id' => $nextItem,
				'related' => $model->related($item, 4)
			]);
		}
		return $html;
	}


	public function all(Model $model, View $view,  Cache $cache, Response $response, $template)
	{
		$response->header('Cache-Control', 'max-age=60, 60, public');
		$key = $template;
		$response->header('Content-type',  'text/xml');
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('article/' . $template, [
				'items' => $model->recent(50),
			]);
			$cache->set($key, $html, 10 * 60);

			return $html;
		}
	}

	public function rss($section, Model $model, View $view,  Cache $cache, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$response->header('Content-type',  'text/xml');

		$map = [
			'arts-and-life' => 'city',
			'in-depth' => 'indepth'
		];

		if(isset($map[$section])) {
			$section = $map[$section];
		}
		$key = 'rss_section_' . $section;

		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('article/rss', [
				'section' => $section,
				'items' => $section == 'podcasts' ? $model->type('podcast', 50) : $model->section($section,  50),
			]);
			$cache->set($key, $html, 10 * 60);
			return $html;
		}
	}


	public function feed($section = null, Model $model, View $view,  Cache $cache, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$response->header('Content-type',  'text/xml');

		$key = 'feed';

		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('article/feed', [
				'items' => $model->recent(50),
			]);
			$cache->set($key, $html, 5 * 60);
			return $html;
		}
	}



	public function yandexnews(Model $model, View $view,  Cache $cache, Response $response)
	{
		$key = 'yandex-news';
		$response->header('Content-type',  'text/xml');
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('article/yandex-news', [
				'items' => $model->recent(50),
			]);
			$cache->set($key, $html, 10 * 60);
			return $html;
		}
	}



	public function type($type, $offset = 0, Model $model, View $view,  Cache $cache, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$key = 'type_' . $type . '_' . $offset;
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render($offset > 0 ? 'article/more' : 'article/index', [
				'section' => $type,
				'tag' => null,
				'items' => $model->type($type, 20, $offset)
			]);
			$cache->set($key, $html, 10 * 60);
			return $html;
		}
	}


	public function podcasts(Model $model, View $view,  Cache $cache, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$key = 'podcasts';
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('article/podcasts', [
				'authors' => $model->podcasts(5)
			]);
			$cache->set($key, $html, 5 * 60);
			return $html;
		}
	}


	public function tag($tag, $offset = 0, Model $model, TagModel $tagModel,  View $view,   Cache $cache, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$key = 'tag_' . $tag . '_' . $offset;
		if($html = $cache->get($key)) {
			return $html;
		} else {
			if($tag = $tagModel->one($tag)) {
				$html = $view->render($offset > 0 ? 'article/more' : 'article/index', [
					'section' => 'tag',
					'tag' => $tag->title,
					'items' => $model->tag($tag->id, 20, $offset),
					'events' => [],
				]);
				$cache->set($key, $html, 10 * 60);
				return $html;
			} else {
				return $view->render('page/404');
			}
		}
	}


	/**
	 * Redirects of old urls
	 * @param type $section
	 * @param type $slug
	 * @param Response $response
	 * @param Url $url
	 * @param \Article\Model $model
	 * @param View $view
	 * @return type
	 */
	public function redirect($section = null, $slug = null, Response $response, Url $url, Model $model, View $view)
	{
		if($slug !== null) {
			$parts = explode('-', $slug);
			$id = array_pop($parts);
			if(is_numeric($id)) {
				if($article = $model->id($id)){
					$response->redirect($url->route('article', $article->data()));
				}
			}
		}
		return $view->render('page/404');
	}
}