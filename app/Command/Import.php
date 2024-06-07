<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Data;
use Sulfur\Config;

class Import extends Command
{
	protected $migration;

	/**
	 * Creation command
	 * @param DataMigration $migration
	 * @param \Sulfur\Config $config
	 */
	public function __construct(Data $data, Config $config)
	{
		var_dump('ok');
	}


	/**
	 * Command description
	 * @return string
	 */
	public function description()
	{
		return  'Import';
	}


	/**
	 * Handle the command
	 */
	public function handle()
    {
		var_dump('ok');
		/*
		// get the changes
		$old = $this->old();
		$new = $this->migration->schema($this->config->migration('entities'));
		$changes = $this->migration->diff($old, $new);

		// prepare for writing
		$time = date('YmdHis', time());
		$class = 'Sulfur' . substr(md5($time),0,8);

		// create a Phinx migration
		$contents = $this->phpMigration($new, $changes, $class);

		// write migration class
		$file = $this->config->phinx('paths.migrations') . '/' . $time . '_' . lcfirst($class) . '.php';
		file_put_contents($file, $contents);

		// write updated schema
		file_put_contents(
			$this->config->migration('schema') . $time . '_schema.json',
			json_encode($new, JSON_PRETTY_PRINT), FILE_APPEND
		);

		// write output
		$this->write([
			'Created Phinx migration file',
			'==============================',
			'File: ' . $time . '_' . lcfirst($class) . '.php',
			'At: ' . $this->config->phinx('paths.migrations'),
			'Check the file to make sure there are no unwanted drops or renames',
			'If everything is ok, run "phinx migrate" to send the changes to the database',
			'If it\'s not ok, change the migration file to correctly reflect the state of the entity files and then run "phinx migrate"'
		]);
		*/

    }
}