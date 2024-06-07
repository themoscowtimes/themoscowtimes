<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Config;
use Sulfur\Filesystem;
use Url;
use Sulfur\View;
use View\Helper;
use Sulfur\Data;

class Feed extends Command
{
	/**
	 *
	 * @var \Sulfur\Filesystem
	 */
	protected $filesystem;

	/**
	 * Creation command
	 * @param DataMigration $migration
	 * @param \Sulfur\Config $config
	 */
	public function __construct(Config $config, Url $url, Filesystem $filesystem, View $view, Helper $helper, Data $data)
	{
		$this->config = $config;
		$this->url = $url;
		$this->filesystem = $filesystem;
		$this->view = $view;
		$helper->register();
		$this->data = $data;
	}


	/**
	 * Handle the command
	 */
	public function handle()
    {

		$articles = $this->data->finder('Article\Entity')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->where('sponsored', 0)
		->where('living', 0)
		->where('type', 'in', ['default', 'gallery', 'video'])

		->limit(20000)
		->offset(0)

		->where('status', 'live')
		->where('time_publication', '<', date('Y-m-d H:i:s'))
		->order('time_publication', 'desc')
		->all();


		$html = $this->view->render('article/feed', [
			'items' => $articles,
		]);

		$file = 'www/public/sitemap/feed_1.xml';

		if($this->filesystem->has($file)) {
			$this->filesystem->delete($file);
		}
		$this->filesystem->write($file, $html);
	}
}