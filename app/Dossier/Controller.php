<?php

namespace Dossier;

use Sulfur\View;
use Dossier\Model;
use Sulfur\Form;
use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Cache;

class Controller
{

	public function preview(Request $request, Model $model, View $view)
	{
		if($item = $model->preview($request->post())) {
			return $view->render('dossier/item', [
				'item' => $item,
				'articles' => $item->articles,
			]);
		}
	}


	public function view($slug, $offset = 0, Model $model, View $view, Cache $cache, Response $response)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		$key = 'dossier_' . $slug . '_' . $offset;		
		if($html = $cache->get($key)) {
			return $html;
		} else {

			if($item = $model->one($slug, 18, $offset)) {
				$html = $view->render($offset > 0 ? 'dossier/more' : 'dossier/item', [
					'item' => $item,
					'articles' => $item->articles,
				]);
				$cache->set($key, $html, 10 * 60);
				return $html;
			} else {
				return $view->render('page/404');
			}
		}
	}
}