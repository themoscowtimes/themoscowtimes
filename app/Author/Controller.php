<?php

namespace Author;

use Sulfur\View;
use Sulfur\Response;
use Url;

class Controller
{
	public function view($slug, Model $model, View $view, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		if($item = $model->one($slug)) {
			return $view->render('author/item', [
				'item' => $item,
				'articles' => $model->articles($item->id, 100)
			]);
		} else {
			return $view->render('page/404');
		}
	}

	public function authors($char, Model $model, View $view)
	{
		return $view->render('author/index', [
			'authors' => $model->all($char),
			'index' => $char
		]);
	}

	public function redirect($id, Response $response, Url $url, Model $model, View $view)
	{
		if(is_numeric($id)) {
			if($author = $model->id($id)){
				$response->redirect($url->route('author', ['slug' => $author->slug]));
			}
		}
		return $view->render('page/404');
	}
}