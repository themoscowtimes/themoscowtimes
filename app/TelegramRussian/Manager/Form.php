<?php

namespace TelegramRussian\Manager;

use Sulfur\Form\Builder;

class Form extends Builder
{

	public function elements()
	{
		return [
      ['title', 'text'],
			['title_ru', 'text', 'attributes' => ['readonly' => true]],
		];
	}

	public function rules()
	{
		return [
			['title', 'required']
		];
	}


	public function processors()
	{
		return [];
	}


	public function layout()
	{
		return [
			['column', [
        'title',
				'title_ru',
			]]
		];
	}
}