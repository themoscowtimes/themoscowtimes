<?php

namespace HomeCarousel;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
    $this->data = $data;
	}

	public function all()
	{
		return $this->data->finder('HomeCarousel\Entity')
		->with('title')
		->with('article')
		->where('status', 'live')
		->order('rank', 'asc')
    ->all();
	}
}