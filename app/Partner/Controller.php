<?php

namespace Partner;

use Sulfur\View;
use Sulfur\Response;
use Url;

class Controller
{
	public function view($slug, Model $model, View $view, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		if($item = $model->one($slug)) {
			return $view->render('partner/item', [
				'item' => $item,
				'articles' => $model->articles($item->id, 100)
			]);
		} else {
			return $view->render('page/404');
		}
	}


	public function redirect($id, Response $response, Url $url, Model $model, View $view)
	{
		if(is_numeric($id)) {
			if($partner = $model->id($id)){
				$response->redirect($url->route('partner', ['slug' => $partner->slug]));
			}
		}
		return $view->render('page/404');
	}
}