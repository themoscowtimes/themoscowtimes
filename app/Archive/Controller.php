<?php

namespace Archive;

use Sulfur\View;
use Sulfur\Cache;

class Controller
{

	public function index(Model $model, View $view, Cache $cache)
	{

		$key = 'archive_index';
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('archive/index', [
				'image' => $model->image(),
				'articles' => $model->articles(),
				'history' => $model->history(),
				'issues' =>  $model->issues(),
			]);
			//$cache->set($key, $html, 5 * 60);
			return $html;
		}
	}



	public function template(View $view)
	{
		return $view->render('archive/template', []);

	}
}