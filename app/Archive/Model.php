<?php

namespace Archive;

use Sulfur\Data;
use Article\Model as ArticleModel;
use Archive\Article\Model as ArchiveModel;

class Model
{

	protected $archive;

	public function __construct(Data $data, ArticleModel $articleModel, ArchiveModel $archiveModel)
	{
		$this->data = $data;
		$this->articleModel = $articleModel;
		$this->archiveModel = $archiveModel;
	}


	protected function archive()
	{
		if(! $this->archive) {
			 $this->archive = $this->data->finder('Archive\Entity')
			->with('issues')
			->with('images')
			->where('zone', 'main')
			->one();
		}
		return $this->archive;
	}


	public function image()
	{
		$images = $this->archive()->images;
		if(count($images) > 0) {
			shuffle($images);
			return $images[0];
		}
	}



	public function articles()
	{
		$articles = [];
		foreach($this->archive()->articles as $block) {
			if(isset($block['article']) && isset($block['article']['id'])) {
				if($block['type'] == 'article') {
					$article = $this->data->finder('Article\Entity')->one($block['article']['id']);
				} else {
					$article = $this->data->finder('Archive\Article\Entity')->one($block['article']['id']);
				}
				if($article) {
					$article->archive = $block['type'] == 'archive';
					$articles[] = $article;
				}
			}

		}
		return array_slice($articles, 0, 4);
	}


	public function issues()
	{
		return array_slice($this->archive()->issues, 0, 4);  ;
	}




	public function history()
	{
		$history = [];

		foreach([10, 20, 30] as $years) {
			$time = time() - ($years * 365 * 24 * 3600);

			$from = date('Y-m-d' , $time - 7 * 24 * 3600);
			$to = date('Y-m-d' , $time + 7 * 24 * 3600);

			$articles = $this->data->finder('Archive\Article\Entity')
			->with('image')
			->where('time_publication', '>', $from)
			->where('time_publication', '<', $to)
			->all(true);

			$found = null;
			$closest = 1000000000000;
			foreach($articles as $article) {
				$diff = abs(strtotime($article->time_publication) - $time);
				if($diff < $closest) {
					$found = $article;
					$closest = $diff;
				}
			}

			if($found) {
				$history[$years] = $found;
			}
		}

		return $history;

	}




}