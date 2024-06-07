<?php

namespace Author\Manager;

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
			['slug', 'slug', 'source' => 'title'],
			['body', 'textarea'],
			['email', 'text'],
			['twitter', 'text'],
			['image', 'image', 'multiple' => false,  'module' => 'image',  'max' => 1],
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
				'body',
			]],
			['column', [
				'image',
				'email',
				'twitter',
			]],
			['column', [
				['section', [
					'slug',
				]],
			], 'width' => 4],
		];
	}
}

