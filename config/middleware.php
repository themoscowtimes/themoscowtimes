<?php return [
	Sulfur\Middleware\Fail::class,
	Middleware\Bootstrap::class,
	Middleware\Redirect::class,
	Middleware\Access::class,
	Sulfur\Middleware\Controller::class,
	//Middleware\Manager::class,
	//Middleware\Account::class,
	Middleware\Notfound::class,
];