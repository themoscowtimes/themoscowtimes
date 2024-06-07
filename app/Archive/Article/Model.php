<?php

namespace Archive\Article;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
		$this->data = $data;
	}

	public function preview($post)
	{
		if(isset($post['values'])) {
			$values = json_decode(base64_decode($post['values']), true);

			if(! $values['time_publication']) {
				$values['time_publication'] = date('Y-m-d H:i:s');
			}

			$entity = $this->data->hydrate('Archive\Article\Entity', $values);
			$entity->tags = [];
			return $entity;
		}
	}


	public function id($id)
	{
		return $this->data->finder('Archive\Article\Entity')
		->where('id', $id)
		->one();
	}


	public function one($slug)
	{
		$articles = $this->data->finder('Archive\Article\Entity')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->with('authors.image')
		->with('image')
		->with('images')
		->where('slug', $slug);

		return $articles->one();
	}


	public function date($year, $month, $day)
	{
		$date = $year . '-' . $month . '-' . $day;
		if(strtotime($date)) {
			return $this->data->finder('Archive\Article\Entity')
			->with('authors', function($finder){
				$finder->where('status', 'live');
			})
			->with('image')
			->where('time_publication', '>=', $date . ' 00:00:00')
			->where('time_publication', '<=', $date . ' 23:59:00')
			->all();
		} else {
			return [];
		}
	}


	public function set(array $ids = [])
	{
		if(count($ids) > 0) {
			// escape ids for raw db input
			array_walk($ids, function($id){ return (int) $id; });

			// get finder
			$finder = $this->data->finder('Archive\Article\Entity');

			// get articles
			$articles = $finder
			->with('image')
			->with('authors', function($finder){
				$finder->where('status', 'live');
			})
			->where('id', 'in', $ids)
			->order($finder->raw('FIELD(id,' . implode(',', $ids) . ' )'), 'ASC');

			return $articles->all();
		} else {
			return [];
		}
	}


	public function author($authorId, $amount = 10, $skip = 0)
	{
		$articles = $this->data->finder('Archive\Article\Entity')
		->with('image')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->join('archive_article_author', 'INNER')
		->on('archive_article_author.archive_article_id', 'archive_article.id')
		->onWhere('archive_article_author.archive_author_id', $authorId)
		->limit($amount)
		->offset($skip);

		return $articles->all();
	}


	public function authors($authorIds, $amount = 10, $skip = 0)
	{
		if(count($authorIds) > 0 ){
			$articles = $this->data->finder('Archive\Article\Entity')
			->with('image')
			->with('authors', function($finder){
				$finder->where('status', 'live');
			})
			->join('archive_article_author', 'INNER')
			->on('archive_article_author.archive_article_id', 'archive_article.id')
			->onWhere('archive_article_author.archive_author_id', 'in', $authorIds)
			->limit($amount)
			->offset($skip);

			return $articles->all();
		} else {
			return [];
		}
	}
}