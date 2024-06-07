<?php

namespace Campaign;

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
			$campaign = $this->data->hydrate(
				'Campaign\Entity',
				json_decode(base64_decode($post['values']), true),
				['image', 'logo']
			);

			return $campaign;
		}
	}


	public function one($slug)
	{
		$campaign = $this->data->finder('Campaign\Entity')
		->with('image')
		->with('logo')
		->with('advertorials', function($finder) {
			$finder->where('status', 'live');
		})
		->where('slug', $slug)
		->where('status', 'live')
		->one();

		return $campaign;
	}
}