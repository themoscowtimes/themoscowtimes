<?php

namespace Banner\Manager;

use Sulfur\Form\Builder;

class Form extends Builder
{
	public function attributes()
	{
		return [];
	}

	public function elements()
	{
		return [
			['title', 'text'],
			['type', 'select', 'options' => ['image', 'tag']],
			['positions', 'banner/positions'],
			['image', 'image'],
			['link', 'link'],
			['html', 'textarea'],
		];
	}


	public function rules()
	{
		return [
			['title', 'required']
		];
	}


	public function layout()
	{
		return [
			['column', [
				'title', 'type'
			]],
			['column', [
				'image', 'link', 'html'
			]],
			['column', [
				['section', [
					'positions'
				]],
			], 'width' => 4],
		];
	}
}