<?php
namespace Account\Subscription;

use Sulfur\View;
use Account\Identity;

class Controller
{

	public function index(Model $model, View $view)
	{
		return $view->render('account/subscription/index', [
			'items' => $model->all()
		]);
	}


	public function create(View $view)
	{
		return $view->render('account/subscription/create');
	}


	public function cancel(View $view)
	{
		return $view->render('account/subscription/cancel');
	}

}