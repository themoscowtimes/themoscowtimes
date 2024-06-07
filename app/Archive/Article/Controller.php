<?php

namespace Archive\Article;

use Sulfur\View;
use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Cache;
use Url;



class Controller
{
	public function preview(Request $request, Model $model, View $view)
	{
		if($item = $model->preview($request->post())) {
			return $view->render('article/item', [
				'item' => $item,
				'next_item_id' => 0,
				'related' => [],
				'archive' => true,
			]);
		}
	}



	public function view($slug, Model $model, View $view, Cache $cache)
	{
		$key = 'archive_' . $slug;
		if($html = $cache->get($key)) {
			return $html;
		} elseif($item = $model->one($slug)){
			$item->tags = [];
			$html = $view->render('article/item', [
				'item' => $item,
				'next_item_id' => 0,
				'related' => [],
				'archive' => true
			]);
			$cache->set($key, $html, 5 * 60);
			return $html;
		} else {
			return $view->render('page/404');
		}
	}


	public function index($year, $month, $day, Model $model, View $view, Cache $cache)
	{

		$key = 'archive_' . $year . '_' . $month . '_' . $day;
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('archive/article/index', [
				'items' => $model->date($year, $month, $day),
				'year' => $year,
				'month' => $month,
				'day' => $day,
			]);
			$cache->set($key, $html, 5 * 60);
			return $html;
		}
	}

	public function redirect($html, Model $model, Url $url, Response $response, View $view)
	{
		$parts = explode('.', $html);
		if(isset($parts[0]) && is_numeric($parts[0])) {
			if($item = $model->id($parts[0])) {
				$response->redirect($url->route('archive_article', ['slug' => $item->slug]));
			}
		}
		return $view->render('page/404');
	}
}