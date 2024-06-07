<?php

namespace Location;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
		$this->data = $data;
	}


	public function preview($post)
	{
		return $this->data->hydrate('Location\Entity', json_decode(base64_decode($post['values']), true));
	}


	public function one($slug)
	{
		$locations =  $this->data->finder('Location\Entity')
		->with('image')
		->where('slug', $slug);

		$this->live($locations);

		return $locations->one();
	}


	public function all()
	{
		$locations =  $this->data->finder('Location\Entity')
		->with('image');

		$this->live($locations);

		return $locations->all();
	}


	public function live($finder)
	{
		return $finder->where('status', 'live');
	}
}