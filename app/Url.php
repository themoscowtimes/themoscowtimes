<?php

use Sulfur\Url as BaseUrl;
use Sulfur\Request;
use Sulfur\Config;

class Url
{

	protected $url;
	protected $request;

	public function __construct(BaseUrl $url, Request $request, Config $config)
	{
		$this->url = $url;
		$this->request = $request;
		$this->config = $config;
	}


	public function route($route, array $params = [], array $parts = [])
	{

		if($route == 'article') {
			if(isset($params['time_publication'])) {
				$time = strtotime($params['time_publication']);
			} else {
				$time = time();
			}
			$params['year'] = $params['year'] ?? date('Y', $time);
			$params['month'] = $params['month'] ?? date('m', $time);
			$params['day'] = $params['day'] ?? date('d', $time);
			$params['section'] = $params['section'] ?? 'news';
			$params['slug'] = $params['slug'] . (isset($params['id']) ?  ('-a' . $params['id']) : '');
		}

		/*
		if($route == 'archive_author') {
			$params['slug'] = urlencode($params['title']) . (isset($params['id']) ?  ('-a' . $params['id']) : '');
		}
		*/
		if($route === 'img') {
			$parts['base'] = $this->config->env('url.static');
		}

		if($route === 'image' || $route === 'file' || $route == 'image_archive') {
			// serve iamges from static
			$parts['base'] = $this->config->env('url.static');

			if(isset($params['preset']) && $params['preset'] == 'og' && isset($params['file'])) {
				// For Og images
				// file contains the article id. Create a link to a generated image
				$hash = md5($params['file']);
				$path = substr($hash, 0, 2);
				$params['path'] = $path;
				$params['file'] = $params['file'] . '__' . $hash . '.jpg';
			}
		} elseif(! isset($parts['base'])) {
			// no explicit base provided,
			// set one here, based on zone or configuration
			$parts['base'] = $this->base($params['zone'] ?? null);
		}



		return $this->url->route($route, $params, $parts);
	}


	public function base($zone = null)
	{
		$zones = $this->config->zones();
		if($zone != null && isset($zones[$zone]) && isset($zones[$zone]['base'])) {
			$base = trim($zones[$zone]['base'], '/');

			if($base !== '') {
				$base = $base . '/';
			}

			if(strpos($base, 'http') === 0) {
				// base is given as a full url
				return $base;
			} else {
				// base is given as a path: put the configured base-url in front of it
				 $this->config->env('url.base') . $base;
			}
		} else {
			// no zone: return the configured base url
			return  $this->config->env('url.base');
		}
	}

	public function current()
	{
		return $this->url->current(true);
	}
}