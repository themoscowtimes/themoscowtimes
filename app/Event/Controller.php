<?php

namespace Event;

use Sulfur\View;
use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Filesystem;

use Article\Model as ArticleModel;

class Controller
{

	public function preview(Request $request, Model $model, View $view)
	{
		return $view->render('event/item', [
			'item' => $model->preview($request->post()),
		]);
	}


	public function view($slug, Model $model, View $view, ArticleModel $article, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		if($item = $model->one($slug)) {
			return $view->render('event/item', [
				'item' => $item,
				'events' => $model->all(),
				'city' => $article->section('city', 5),
			]);
		} else {
			return $view->render('page/404');
		}
	}


	public function index(Model $model, View $view, ArticleModel $article, Filesystem $filesystem, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		return $view->render('event/index', [
			'json' => $filesystem->read('events/all.json'),
			'city' => $article->section('city', 5),
		]);

	}
}