<?php

namespace Archive\Author;

use Sulfur\View;
use Sulfur\Response;
use Url;

class Controller
{
	public function view(Model $model, View $view, Response $response, $slug)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$items = $model->slug($slug);
		if(count($items) > 0) {
			return $view->render('archive/author/item', [
				'item' => $items[0],
				'articles' => $model->articles(array_column($items, 'id'), 500)
			]);
		} else {
			return $view->render('page/404');
		}


	}
}