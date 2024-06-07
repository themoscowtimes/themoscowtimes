<?php

namespace Event;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
		$this->data = $data;
	}


	public function preview($post)
	{
		return $this->data->hydrate('Event\Entity', json_decode(base64_decode($post['values']), true));
	}


	public function one($slug)
	{
		$events = $this->data->finder('Event\Entity')
		->with('image')
		->with('locations')
		->where('slug', $slug);

		$this->live($events);
		$this->upcoming($events);

		return $events->one();
	}


	public function all($amount = 10)
	{
		$events = $this->data->finder('Event\Entity')
		->with('image')
		->with('locations')
		->limit($amount);

		$this->live($events);
		$this->upcoming($events);

		return $events->all();
	}


	public function live($finder)
	{
		return $finder->where('status', 'live');
	}


	public function upcoming($finder)
	{
		return $finder->order('time', 'asc')
		->where('time', '>', date('Y-m-d 00:00:00'));
	}

}