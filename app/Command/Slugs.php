<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Config;
use Sulfur\Manager\Slug;
use Sulfur\Database;

class Slugs extends Command
{


	/**
	 * Creation command
	 * @param DataMigration $migration
	 * @param \Sulfur\Config $config
	 */
	public function __construct(Config $config, Slug $slug, Database $database)
	{
		$this->config = $config;
		$this->slug = $slug;
		$this->database = $database;
	}



	public function handle()
    {


		$slugs = $this->database->select('slug')
		->from('article')
		->group('slug')
		->having($this->database->raw('count(id)'), '>', 1)
		->result();

		// $this->slug->clean($item['name']),
		var_dump($slugs);

		foreach($slugs as $slug) {
			$articles = $this->database
			->select()
			->from('article')
			->where('slug', $slug['slug'])
			->result();


			foreach($articles as $article) {
				$slug = $article['slug'] . '-'. $article['id'];

				$query = $this->database->update('article')
				->set(['slug' => $slug])
				->where('id', $article['id']);
				var_dump($query->compile());

				// $query->execute();

			}
		}

	}

}