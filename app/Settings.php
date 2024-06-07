<?php

use Sulfur\Request;
use Sulfur\Data;
use Sulfur\Cache;

class Settings
{

	protected $data;
	protected $request;
	protected $settings = null;

	public function __construct(Data $data, Request $request, Cache $cache)
	{
		$this->data = $data;
		$this->request = $request;
		$this->cache = $cache;
	}


	public function get($name)
	{
		if($this->settings === null) {
			$key = 'settings';

			if($settings = $this->cache->get($key)) {
				$this->settings = $settings;
			} else {
				$this->settings = $this->data->finder('Sulfur\Manager\Settings\Entity')
				->where('zone', 'main')
				->one()
				->data();

				$this->cache->set($key, $this->settings, 600);
			}
		}
		return isset($this->settings[$name]) ? $this->settings[$name] : null;
	}
}