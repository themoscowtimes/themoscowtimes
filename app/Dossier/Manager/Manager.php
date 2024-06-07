<?php

namespace Dossier\Manager;

use Sulfur\Manager\Manager as BaseManager;

class Manager extends BaseManager
{
	protected function prepare($zone, $module, $filter, $sort, $search, $skip, $amount)
	{
		return parent::prepare($zone, $module, $filter, $sort, $search, $skip, $amount)->without('articles');
	}
}