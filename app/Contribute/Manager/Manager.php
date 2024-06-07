<?php

namespace Contribute\Manager;

use Sulfur\Manager\Manager as BaseManager;
use Sulfur\Manager\Payload;

use Contribute\Mollie;

class Manager extends BaseManager
{

	public function __construct(Mollie $mollie)
	{
		$this->mollie = $mollie;
	}

	public function index($zone, $module)
	{
		return new Payload('contribute/customers', [
			'customers' => $this->mollie->customers()
		]);
	}

}