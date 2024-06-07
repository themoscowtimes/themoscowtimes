<?php

namespace Middleware;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Middleware;

use Settings;

class Redirect implements Middleware
{

	protected $settings;

	public function __construct(Settings $settings)
	{
		$this->settings = $settings;
	}


	public function __invoke(Request $request, Response $response, Callable $next)
	{

		$map = [
			
		];
		$path = trim($request->path(false), '/');
		if(isset($map[$path])){
			$response->redirect('/' . trim($redirect[$path], '/'));
			return $response;
		}


		$redirect = $this->settings->get('redirect');

		if(isset($redirect[$path])) {
			$response->redirect('/' . trim($redirect[$path], '/'));
			return $response;
		} else {
			return $next($request, $response);
		}
	}
}

