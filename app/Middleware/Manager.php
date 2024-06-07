<?php

namespace Middleware;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Middleware;
use Sulfur\Container;

use Url;

class Manager implements Middleware
{
	protected $container;
	protected $url;

	public function __construct(Container $container, Url $url)
	{
		$this->container = $container;
		$this->url = $url;
	}


	public function __invoke(Request $request, Response $response, Callable $next)
	{
		if (isset($_COOKIE['sessionid'])) {
			$identity = $this->container->get('Sulfur\Identity');
			if ($identity->confirmed()) {
				$response->body(preg_replace_callback('#\<\!\-\-\[{3}([^\:]+)\:([^\]]+)\]{3}\-\-\>#', function($matches){
					return '<a href="'. $this->url->base() .'manager/main/' . $matches[1] . '/update/' . $matches[2] . '" target="_blank" class="manager-edit" style=""><i class="fa fa-pencil manager-edit__icon"></i><span class="manager-edit__text">&nbsp;edit item</span></a>';
				}, $response->body()));
			}
		}
		return $next($request, $response);
	}
}