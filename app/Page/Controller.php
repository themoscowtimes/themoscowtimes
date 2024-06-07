<?php

namespace Page;

//use Sulfur\Twig;
use Sulfur\View;
use Page\Model;
use Sulfur\Form;
use Sulfur\Request;

class Controller
{


	public function preview(Request $request, Model $model, View $view)
	{
		if($item = $model->preview($request->post())) {
			return $view->render('page/item', [
				'item' => $item,
			]);
		}
	}

	
	public function view($slug, Model $model, View $view)
	{
		if($item = $model->one($slug)) {
			return $view->render('page/item', [
				'item' => $item,
			]);
		} else {
			return $view->render('page/404');
		}
	}
}