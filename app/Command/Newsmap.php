<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Config;
use Sulfur\Filesystem;

use Article\Model;
use Url;

class Newsmap extends Command
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
		$sections = [
			'news',
			'opinion',
			'meanwhile',
			'city',
			'business',
			'climate',
			'diaspora',
			'ukraine_war',
			'lecture_series'
		];

		foreach($sections as $section) {
			$this->section($section);
		}

		$bundle = '<?xml version="1.0" encoding="UTF-8"?>' .  PHP_EOL .
		'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
		foreach($sections as $section) {
			$bundle .= '    <sitemap>' . PHP_EOL .
			'        <loc>' . $this->config->env('url.static') . 'sitemap/' . $section . '.xml</loc>' . PHP_EOL .
			'    </sitemap>' . PHP_EOL;
		}
		$bundle .= '</sitemapindex>';

		$file = 'newsmap.xml';
		if($this->filesystem->has($file)) {
			$this->filesystem->delete($file);
		}
		$this->filesystem->write($file, $bundle);
	}



	protected function section($section)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?>'.  PHP_EOL .
		'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">'.  PHP_EOL;

		foreach($this->model->section($section, 100) as $article) {

			if(strtotime($article->time_publication) < time() - (24 * 30 * 3600)) {
				continue;
			}

			$tags = [];
			foreach($article->tags as $tag) {
				$tags[] = $tag->title;
			}


			$xml .= '    <url>' .  PHP_EOL .
			'        <loc>' . $this->url->route('article', $article->data())  . '</loc>' . PHP_EOL .
			'        <news:news>' . PHP_EOL .
			'           <news:publication>' . PHP_EOL .
            '                <news:name>The Moscow Times</news:name>' . PHP_EOL .
            '                <news:language>en</news:language>' . PHP_EOL .
            '            </news:publication>' . PHP_EOL .
			'            <news:publication_date>' . date('c', strtotime($article->time_publication)) . '</news:publication_date>' . PHP_EOL .
            '            <news:title>' . $article->title . '</news:title>' . PHP_EOL .
            '            <news:keywords>' . ($article->seo['keyword'] ?? implode(',', $tags)) . '</news:keywords>' . PHP_EOL .
			'        </news:news>' .  PHP_EOL .
			'        <changefreq>hourly</changefreq>' .  PHP_EOL .
			'   </url>' .  PHP_EOL;

		}
		$xml .= '</urlset>';

		$file = $section . '.xml';
		if($this->filesystem->has($file)) {
			$this->filesystem->delete($file);
		}
		$this->filesystem->write($file, $xml);
	}
}