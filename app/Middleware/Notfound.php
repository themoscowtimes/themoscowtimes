<?php

namespace Middleware;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Middleware;
use Sulfur\Logger;
use Sulfur\Config;


class Notfound implements Middleware
{

	/**
	 *
	 * @var Sulfur\Logger
	 */
	protected $logger;

	protected $config;

	public function __construct(Logger $logger, Config $config)
	{
		$this->logger = $logger;
		$this->config = $config;
	}

	public function __invoke(Request $request, Response $response, Callable $next)
	{
		// get handler
		$handler = $request->get('handler');

		// Check if it was meant for a controller: if not, go to a 404
		if( ! $handler || strpos($handler, '@') === false){
			$this->logger->error($request->path(), isset($_SERVER['HTTP_USER_AGENT']) ? [$_SERVER['HTTP_USER_AGENT']] : []);
			$response->status(404, 'Page not found');
			$response->header('Cache-Control', 'max-age=300, 300, public');
			$file = $this->config->fail('page');
			if(file_exists($file)) {
				$response->body(file_get_contents($file));
			}
		}

		return $next($request, $response);
	}
}