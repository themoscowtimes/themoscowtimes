<?php

namespace Location;

use Sulfur\View;
use Sulfur\Request;

use Article\Model as ArticleModel;

class Controller
{

	public function preview(Request $request, Model $model, View $view)
	{
		return $view->render('location/item', [
			'item' => $model->preview($request->post()),
		]);
	}


	public function view($slug, Model $model, View $view, ArticleModel $article)
	{
		if($item = $model->one($slug)) {
			return $view->render('location/item', [
				'item' => $item,
				'events' => [],
				'city' => $article->section('city', 5),
			]);
		} else {
			return $view->render('page/404');
		}
	}


	public function index(Model $model, View $view, ArticleModel $article)
	{
		return $view->render('location/index', [
			'items' => $model->all(),
			'city' => $article->section('city', 5),
		]);
	}
}