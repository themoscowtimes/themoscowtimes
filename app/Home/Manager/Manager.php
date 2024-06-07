<?php

namespace Home\Manager;

use Sulfur\Manager\Single\Manager as BaseManager;

use Sulfur\Cache;

class Manager extends BaseManager
{

	/**
	 * @var Sulfur\Cache
	 */
	protected $cache;

	public function __construct(Cache $cache)
	{
		$this->cache = $cache;
	}


	public function save($zone, $module, $id = null)
	{
		$payload = parent::save($zone, $module, $id);
		$this->cache->delete('home');
		return $payload;
	}
}