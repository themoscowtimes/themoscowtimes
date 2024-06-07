<?php

namespace Middleware;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Middleware;

use Sulfur\Container;
use Url;

class Access implements Middleware
{

	public function __construct(Container $container, Url $url)
	{
		// Get stuff from container, so it wont be loaded on each request
		$this->container = $container;
		$this->url = $url;
	}

	/**
	 * Check access to application
	 * @param Request $request
	 * @param Response $response
	 * @param \Middleware\callable $next
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, Callable $next)
	{

		if($request->get('authenticated')) {
			// Request needs to be authenticated

			// Whether it was an api request
			$isApi = strpos($request->get('handler'), '\Api') > 0;
			$api = $this->container->get('Api');
			$identity = $this->container->get('Account\Identity');

			// check if identity confirmed
			if($identity->confirmed()) {
				// get account
				$account = $identity->account();

				// check if account confirmed
				if($this->container->get('Account\Model')->confirmed($account)) {
					// It's good: handle rest of the request
					return $next($request, $response);
				} else {
					if($isApi) {
						// let api respond
						$api->fail($response, 'unconfirmed');

						// stop middleware
						return $response;
					} else {
						// browser: redirect to confirmation
						$response->redirect($this->url->route('confirmation'));
					}
				}
			} else {
				// identity not confirmed
				if($isApi) {
					// let api respond
					$api->fail($response, 'unauthorized');
					// stop middleware
					return $response;
				} else {
					// browser: redirect to signin
					$response->redirect($this->url->route('signin'));
				}
			}
		} else {
			// handle rest of the request
			return $next($request, $response);
		}
	}
}