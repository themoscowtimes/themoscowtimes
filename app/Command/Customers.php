<?php

namespace Command;

use Sulfur\Console\Command;
use Account\Mollie;

class Customers extends Command
{

	public function __construct(Mollie $mollie)
	{
		$this->mollie = $mollie;
	}

	public function handle()
    {
		$this->mollie->import();
	}

}