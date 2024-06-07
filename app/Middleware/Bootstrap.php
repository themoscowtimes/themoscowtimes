<?php

namespace Middleware;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Middleware;

use Lang;
use View\Helper;

class Bootstrap implements Middleware
{

	protected $lang;
	protected $helper;
	protected $url;
	protected $config;

	public function __construct(Helper $helper, Lang $lang)
	{
		$this->lang = $lang;
		$this->helper = $helper;
	}


	public function __invoke(Request $request, Response $response, Callable $next)
	{
		$this->lang->setLang('en');
		$this->helper->register($request);
		return $next($request, $response);
	}
}