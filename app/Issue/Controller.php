<?php

namespace Issue;

use Sulfur\View;
use Sulfur\Request;
use Sulfur\Response;
use Url;
use Article\Model as ArticleModel;

class Controller
{

	public function preview(Request $request, Model $model, View $view)
	{
		return $view->render('issue/item', [
			'item' => $model->preview($request->post()),
		]);
	}


	public function view($number, Model $model, View $view,  Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		if($item = $model->one($number)) {
			return $view->render('issue/item', [
				'item' => $item,
			]);
		} else {
			return $view->render('page/404');
		}
	}


	public function index($offset = 0, Model $model, View $view, ArticleModel $articleModel, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		return $view->render($offset > 0 ? 'issue/more' : 'issue/index', [
			'items' => $model->all(21, $offset),
			'recent' => $articleModel->recent(5),
		]);
	}


	public function redirect($number = null, Response $response, Url $url, Model $model, View $view)
	{
		if($number !== null) {
			if($issue = $model->number($number)){
				$response->redirect($url->route('issue', ['number' => $issue->number]));
			}
		} else {
			$response->redirect($url->route('issues'));

		}
		return $view->render('page/404');
	}
}