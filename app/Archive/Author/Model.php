<?php

namespace Archive\Author;

use Archive\Article\Model as Article;
use Sulfur\Data;

class Model
{
	public function __construct(Data $data, Article $article)
	{
		$this->data = $data;
		$this->article = $article;
	}





	public function set(array $ids = [])
	{
		if(count($ids) > 0) {
			// escape ids for raw db input
			array_walk($ids, function($id){ return (int) $id; });

			// get finder
			$finder = $this->data->finder('Archive\Author\Entity');

			// get authors
			$authors = $finder
			->where('id', 'in', $ids)
			->order($finder->raw('FIELD(id,' . implode(',', $ids) . ' )'), 'ASC');
			return $authors->all();
		} else {
			return [];
		}
	}


	public function slug($slug)
	{
		return $this->data->finder('Archive\Author\Entity')
		->where('normalized', $slug)
		->all(true);
	}



	public function articles($authorIds, $amount = 10)
	{
		return $this->article->authors($authorIds, $amount);
	}
}