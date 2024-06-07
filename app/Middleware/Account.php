<?php

namespace Middleware;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Middleware;
use Sulfur\Container;
use Sulfur\Config;
use Sulfur\View;


class Account implements Middleware
{
	protected $container;
	protected $config;
	protected $view;

	public function __construct(Container $container, Config $config, View $view)
	{
		$this->container = $container;
		$this->config = $config;
		$this->view = $view;
	}


	public function __invoke(Request $request, Response $response, Callable $next)
	{
		if (isset($_COOKIE[$this->config->session('name')])) {
			$identity = $this->container->get('Account\Identity');
			if($identity->confirmed()) {
				$accountModel = $this->container->get('Account\Model');
				$response->body(str_replace(
					'aria-label="[[account]]"',
					'y-use="Account" data-letter="' . htmlentities(substr($accountModel->email($identity->account()), 0, 1)) . '"',
					$response->body()
				));
			}
		}
		return $next($request, $response);
	}
}