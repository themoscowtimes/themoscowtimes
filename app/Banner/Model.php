<?php

namespace Banner;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
		$this->data = $data;
	}

	public function all()
	{
		return $this->data->finder('Banner\Entity')
		->with('image')
		->where('status', 'live')
		->all();
	}
}