<?php

namespace Home;

use Sulfur\Data;
use Article\Model as ArticleModel;
use Event\Model as EventModel;
use Issue\Model as IssueModel;
use Dossier\Model as DossierModel;
use Advertorial\Model as AdvertorialModel;

class Model
{
	protected $data = null;

	protected $articleModel = null;

	protected $eventModel = null;

	protected $issueModel = null;

	protected $dossierModel = null;

	protected $home = null;

	public function __construct(
		Data $data,
		ArticleModel $articleModel,
		EventModel $eventModel,
		IssueModel $issueModel,
		DossierModel $dossierModel,
		AdvertorialModel $advertorialModel
	)
	{
		$this->data = $data;
		$this->articleModel = $articleModel;
		$this->eventModel = $eventModel;
		$this->issueModel = $issueModel;
		$this->dossierModel = $dossierModel;
		$this->advertorialModel = $advertorialModel;
	}

	public function preview($post)
	{
		if(isset($post['values'])) {
			$home = $this->data->hydrate(
				'Home\Entity',
				json_decode(base64_decode($post['values']), true),
				[
					'today.images',
					'highlights.images',
				]
			);

			$today = [];
			foreach($home->today as $article) {
				if($article->status == 'live' && strtotime($article->time_publication) < time()) {
					$today[] = $article;
				}
			}
			$home->today = $today;


			$highlights = [];
			foreach($home->highlights as $article) {
				if($article->status == 'live'  && strtotime($article->time_publication) < time()) {
					$highlights[] = $article;
				}
			}
			$home->highlights = $highlights;



			return $home;
		}
	}


	public function today($amount = 10)
	{
		return array_slice($this->home()->today, 0 , $amount);
	}


	public function highlights($amount = 10)
	{
		return array_slice($this->home()->highlights, 0 , $amount);
	}

	public function sponsored($amount = 1)
	{
		return $this->articleModel->section('sponsored', $amount)->flat();
	}

	public function feature()
	{
		$home = $this->home();
		return $home->article_feature;
	}

	public function pick()
	{
		$home = $this->home();
		return $home->article_pick;
	}

	public function live()
	{
		$home = $this->home();
		return $home->article_live;
	}
	

	public function photosVideos($amount = 10)
	{
		$videos = $this->articleModel->type('video', $amount)->flat();
		$photos = $this->articleModel->type('gallery', $amount)->flat();
		// merge two arrays
		$merged = array_merge($photos, $videos);
		// sort in descending order by date
		usort($merged, function($a, $b)
		{
			return strtotime($b['created']) - strtotime($a['created']);
		});
		return $merged;
	}

	public function section($section, $amount = 10)
	{
		$home = $this->home();
		$articles = $this->articleModel->section($section, $amount);
		if($article = $home->{'article_' . $section}) {
			if(is_object($articles)){
				$articles = $articles->flat();
			}
			// check time
			if(time() < strtotime($home->{'article_' . $section . '_end'})) {
				if($section == 'city') {
					// add in city as the third
					array_splice($articles, 2, 0, [$article]);
				} else {
					// add selected article to the front
					array_unshift($articles, $article);
				}
				// remove the last one
				array_pop($articles);
			}
		}
		return $articles;
	}


	public function events($amount = 10)
	{
		return $this->eventModel->all($amount);
	}


	public function issues($amount = 10)
	{
		return $this->issueModel->latest($amount);
	}


	public function podcasts($amount = 10)
	{
		return $this->articleModel->type('podcast', $amount)->flat();
	}

	 public function videos($amount = 20)
	{
		return $this->articleModel->type('video', $amount)->flat();
	}

	public function dossier($amount = 4, $name = null)
	{
		if($name !== null) {
			$home = $this->home();
			if($dossier = $home->{$name}) {
				if($dossier = $this->dossierModel->id($dossier->id, $amount)) {
					$dossier->location =  $home->{$name . '_location'};
					return $dossier;
				}
			}
		}
	}


	public function advertorials($amount = 2)
	{
		$advertorials =[];
		foreach($this->advertorialModel->all() as $advertorial) {
			if($advertorial->campaign && $advertorial->campaign->home) {
				$advertorials[] = $advertorial;
			}
		}
		shuffle($advertorials);
		return array_slice($advertorials, 0 ,$amount);
	}


	public function home($home = null)
	{
		if($home === null) {
			if($this->home === null) {
				$this->home = $this->data->finder('Home\Entity')
				->with('article_feature')
				->with('article_pick')
				->with('article_live', function($finder){
					$finder
					->where('type', 'live')
					->with('image');
					$this->articleModel->live($finder);
				})
				->with('article_opinion')
				->with('article_meanwhile')
				->with('article_business')
				->with('article_climate')
				->with('article_diaspora')
				->with('article_city')
				->with('article_indepth')
				->with('article_living')
				->with('today', function($finder){
					$finder
					->with('authors', function($finder){
						$finder->where('status', 'live');
					})
					->with('image');
					$this->articleModel->live($finder);
				})
				->with('highlights', function($finder){
					$finder
					->with('authors', function($finder){
						$finder->where('status', 'live');
					})
					->with('image');
					$this->articleModel->live($finder);
				})
				->where('zone', 'main')
				->one();
			}
			return $this->home;
		} else {
			$this->home = $home;
			return $this;
		}
	}
}