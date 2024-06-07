<?php

namespace Ambassador;

//use Sulfur\Twig;
use Sulfur\View;
use Ambassador\Model;
use Sulfur\Form;
use Sulfur\Request;

class Controller
{


	public function preview(Request $request, Model $model, View $view)
	{
		if($item = $model->preview($request->post())) {
			return $view->render('ambassador/item', [
				'item' => $item,
			]);
		}
	}

	public function index(Model $model, View $view) {
    return $view->render('ambassador/index', ['items' => $model->all()]);
  }


	public function view($slug, Model $model, View $view)
	{
		if ($item = $model->one($slug)) {
			return $view->render('ambassador/item', [
				'item' => $item,
			]);
		} else {
			return $view->render('ambassador/404');
		}
	}
}