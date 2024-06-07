<?php

namespace Dossier;

use Sulfur\Data;

use Article\Model as Article;

class Model
{
	public function __construct(Data $data, Article $article)
	{
		$this->data = $data;
		$this->article = $article;
	}


	public function preview($post)
	{
		if(isset($post['values'])) {
			$dossier = $this->data->hydrate(
				'Dossier\Entity',
				json_decode(base64_decode($post['values']), true),
				['image', 'articles', 'tags']
			);
			$dossier->articles = $this->articles($dossier, 20);
			return $dossier;
		}
	}


	public function one($slug, $articles = 20, $offset = 0)
	{
		$dossier = $this->data->finder('Dossier\Entity')
		->with('image')
		->with('articles')
		->with('tags')
		->where('slug', $slug)
		->where('status', 'live')
		->one();

		if($dossier) {
			$dossier->articles = $this->articles($dossier, $articles, $offset);
		}
		return $dossier;
	}


	public function id($id, $articles = 20)
	{
		$dossier = $this->data->finder('Dossier\Entity')
		->with('image')
		->with('articles')
		->with('tags')
		->where('id', $id)
		->where('status', 'live')
		->one();

		if($dossier) {
			$dossier->articles = $this->articles($dossier, $articles);
		}
		return $dossier;
	}


	public function all()
	{
		return $this->data->finder('Dossier\Entity')
		->with('image')
		->where('status', 'live')
		->all();
	}


	public function articles($dossier, $amount = 10, $offset = 0)
	{
		// stardt with manual articles
		$articles = is_array($dossier->articles) ? $dossier->articles : [];

		// add articles by tag
		foreach($dossier->tags as $tag) {
			foreach($this->article->tag($tag->id, $amount + $offset) as $article) {
				$articles[] = $article;
			}
		}

		// filter doubles and non-live
		$ids = [];
		$articles = array_filter($articles, function($article) use ($ids) {
			if(
				! in_array($article->id, $ids)
				&& strtotime($article->time_publication) < time()
				&& $article->status == 'live'
			) {
				$ids[] = $article->id;
				return true;
			}
		});


		// sort by date
		usort($articles, function($a, $b) {
			return strtotime($a->time_publication) < strtotime($b->time_publication);
		});


		// slice
		$articles = array_slice($articles, $offset, $amount);

		//done
		return $articles;

	}
}