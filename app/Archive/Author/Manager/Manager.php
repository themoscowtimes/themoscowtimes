<?php

namespace Archive\Author\Manager;

use Sulfur\Manager\Manager as BaseManager;

class Manager extends BaseManager
{
	public function index($zone, $module)
	{
		$payload = parent::index($zone, $module);

		// unset search state
		$this->state->set('search', '');
		return $payload;
	}


	protected function finder()
	{
		// dont load the articles, they are not needed
		return parent::finder()->without('articles');
	}
}