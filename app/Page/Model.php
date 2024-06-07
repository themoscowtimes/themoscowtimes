<?php

namespace Page;

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
				'Page\Entity',
				json_decode(base64_decode($post['values']), true),
				['image', 'files']
			);
		}
	}


	public function one($slug)
	{
		return $this->data->finder('Page\Entity')
		->with('image')
		->with('files')
		->where('slug', $slug)
		->where('status', 'live')
		->one();
	}


	public function all()
	{
		return $this->data->finder('Page\Entity')
		->with('image')
		->with('files')
		->where('status', 'live')
		->all();
	}
}