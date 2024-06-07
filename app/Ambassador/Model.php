<?php

namespace Ambassador;

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
			return $this->data->hydrate(
				'Ambassador\Entity',
				json_decode(base64_decode($post['values']), true),
				['image']
			);
		}
	}


	public function one($slug)
	{
		return $this->data->finder('Ambassador\Entity')
		->with('image')
		->where('slug', $slug)
		->where('status', 'live')
		->one();
	}


	public function all()
	{
		return $this->data->finder('Ambassador\Entity')
		->with('image')
		->where('status', 'live')
		->all();
	}
}