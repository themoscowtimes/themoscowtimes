<?php

namespace TelegramRussian;

use Sulfur\Data;

class Model
{

	protected $data = null;

	public function __construct(Data $data)
	{
    $this->data = $data;
	}

	public function all()
	{
		return $this->data->finder('TelegramRussian\Entity')
		->where('status', 'live')
    ->all();
	}
}