<?php

namespace Partner;

use Sulfur\Data;
use Article\Model as ArticleModel;

class Model
{
	public function __construct(Data $data, ArticleModel $article)
	{
		$this->data = $data;
		$this->article = $article;
	}


	public function one($slug)
	{
		return $this->data->finder('Partner\Entity')
		->with('image')
		->where('status', 'live')
		->where('slug', $slug)
		->one();
	}

	public function id($id)
	{
		return $this->data->finder('Partner\Entity')
		->where('id', $id)
		->one();
	}

	public function articles($partnerId, $amount = 10)
	{
		return $this->article->partner($partnerId, $amount);
	}


	public function set(array $ids = [])
	{
		if(count($ids) > 0) {
			// escape ids for raw db input
			array_walk($ids, function($id){ return (int) $id; });

			// get finder
			$finder = $this->data->finder('Partner\Entity');

			// get partners
			$partners = $finder
			->with('image')
			->where('id', 'in', $ids)
			->order($finder->raw('FIELD(id,' . implode(',', $ids) . ' )'), 'ASC');
			return $partners->all();
		} else {
			return [];
		}
	}
}