<?php

namespace Issue;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
		$this->data = $data;
	}


	public function preview($post)
	{
		return $this->data->hydrate('Issue\Entity', json_decode(base64_decode($post['values']), true));
	}

	public function latest($amount = 10)
	{
		$issues = $this->data->finder('Issue\Entity')
		->with('file')
		->with('image')
		->limit($amount)
		->order('date', 'desc');

		$this->live($issues);

		return $issues->all();
	}


	public function number($number)
	{
		return $this->data->finder('Issue\Entity')
		->where('number', $number)
		->one();
	}


	public function one($number)
	{
		$issues = $this->data->finder('Issue\Entity')
		->with('articles')
		->with('file')
		->with('image')
		->where('number', $number);

		$this->live($issues);

		return $issues->one();
	}


	public function all($amount = 20, $offset = 0)
	{
		$issues = $this->data->finder('Issue\Entity')
		->with('file')
		->with('image')
		->limit($amount)
		->offset($offset)
		->order('date', 'desc');

		$this->live($issues);

		return $issues->all();
	}


	public function live($finder)
	{
		return $finder->where('status', 'live');
	}
}