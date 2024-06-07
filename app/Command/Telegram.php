<?php

namespace Command;

use Sulfur\Console\Command;
use TelegramRussian\Import;

class Telegram extends Command
{

	/**
	 * Creation command
	 */
	public function __construct(Import $telegram)
	{
		$this->telegram = $telegram;
	}

	/**
	 * Handle the command
	 */
	public function handle()
  {
		echo 'Import started at ' . date('Y-m-d H:i:s') . PHP_EOL;
		$this->telegram->import();
	}
}