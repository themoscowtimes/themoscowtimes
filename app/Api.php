<?php


use Sulfur\Request;
use Sulfur\Response;

class Api
{
	protected $request;

	protected $config = [
		'token' => []
	];

	public function __construct(Request $request, $config = [])
	{
		$this->request = $request;
		$this->config = $config;
	}


	public function authenticate()
	{
		$header = $this->request->header('Authorization');
		$parts = preg_split('#\s+#', $header);
		if(count($parts) == 2 && strtolower($parts[0]) == 'bearer') {
			$token = $parts[1];
			if(in_array($token, array_values($this->config['token']))) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Get post data
	 */
	public function post($key = null, $default = null)
	{

		$input = file_get_contents('php://input');
		$post = json_decode($input, true);

		if(! is_array($post)) {
			parse_str($input, $post);
		}

		if(is_array($post)) {
			if($key !== null) {
				if(isset($post[$key])) {
					return $post[$key];
				} else {
					return $default;
				}
			} else {
				return $post;
			}
		} else {
			return $key !== null ? $default : [];
		}
	}


	/**
	 * Get qs data
	 */
	public function get($key = null, $default = null)
	{
		if($key !== null) {
			if(isset($_GET[$key])) {
				return $_GET[$key];
			} else {
				return $default;
			}
		} else {
			return $_GET;
		}
	}


	/**
	 * Get array with [ip, useragent]
	 * @return array
	 */
	public function agent()
	{
		return [
			$this->request->ip(),
			$this->request->server('HTTP_USER_AGENT'),
		];
	}


	public function fail(Response $response, $body = '', $status = 400)
	{
		if(! is_string($body)) {
			$body = json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		}
		$response->header('Content-Type', 'text/html' );
		$response->header('Cache-Control', 'no-cache');
		$response->status($status);
		$response->body($body);
	}


	public function respond(Response $response, $body = '', $cache = 'no-cache')
	{
		if(! is_string($body)) {
			$body = json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		}
		$response->status(200);
		$response->header('Content-Type', 'application/json');
		$response->header('Cache-Control', $cache);
		$response->body($body);
	}


	protected function lang($key)
	{
		$map = [
			'' => '',
		];

		if(isset($map[$key])) {
			return $map[$key];
		} else {
			return $key;
		}
	}
}