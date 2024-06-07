<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Config;
use Sulfur\Filesystem;

use Article\Model;
use Url;

class Sitemap extends Command
{

	protected $arguments = ['from'];

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
	public function __construct(Config $config, Url $url, Filesystem $filesystem, Model $model)
	{
		$this->config = $config;
		$this->url = $url;
		$this->filesystem = $filesystem;
		$this->model = $model;
	}


	/**
	 * Handle the command
	 */
	public function handle()
    {
		$from = $this->argument('from');

		if($from && count(explode('-', $from)) > 1) {
			$fromYear = explode('-', $from)[0];
			$fromMonth = explode('-', $from)[1];
		} else {
			$fromYear = date('Y');
			$fromMonth = date('n');
		}


		if(is_numeric($fromYear) && $fromYear > 1994 && is_numeric($fromMonth) && $fromMonth > 0 && $fromMonth <= 12) {
			for($y = $fromYear; $y <= date('Y'); $y++) {
				if($y == $fromYear) {
					// if we are in the fromyear, obey the provided frommonth
					$startMonth = $fromMonth;
				} else {
					// if not, just start at 1
					$startMonth = 1;
				}

				if($y == date('Y')) {
					// if we are in the current year, dont go past the current month
					$endMonth = date('n');
				} else {
					// if not, just go to 12
					$endMonth = 12;
				}
				for($m = $startMonth; $m <= $endMonth; $m++) {
					$this->month($y, $m);
				}
			}

			$this->bundle();

		}
	}


	protected function month($year, $month)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?>'.  PHP_EOL .
		'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">'.  PHP_EOL;

		foreach($this->model->month($year, $month) as $article) {
			$xml .= '    <url>' .  PHP_EOL .
			'        <loc>' . $this->url->route('article', $article->data())  . '</loc>' . PHP_EOL .
			'    </url>' .  PHP_EOL;

		}
		$xml .= '</urlset>';

		$file = $year . '-' . $month . '.xml';
		if($this->filesystem->has($file)) {
			$this->filesystem->delete($file);
		}
		$this->filesystem->write($file, $xml);
	}



	protected function bundle()
	{
		$bundle = '<?xml version="1.0" encoding="UTF-8"?>' .  PHP_EOL .
		'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

		foreach(array_reverse($this->filesystem->contents('')) as $file) {
			if(preg_match('#[0-9]{4}\-[0-9]{1,2}#',$file['filename'])) {
				$bundle .= '    <sitemap>' . PHP_EOL .
				'        <loc>' . $this->config->env('url.static') . 'sitemap/' . $file['basename'] . '</loc>' . PHP_EOL .
				'        <lastmod>' . date('c', $file['timestamp']) . '</lastmod>' . PHP_EOL .
				'    </sitemap>' . PHP_EOL;
			}
		}
		$bundle .= '</sitemapindex>';

		$file = 'sitemap.xml';
		if($this->filesystem->has($file)) {
			$this->filesystem->delete($file);
		}
		$this->filesystem->write($file, $bundle);
	}
}