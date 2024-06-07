<?php

namespace Campaign\Manager;

use Sulfur\Form\Builder;

class Form extends Builder
{

	public function elements()
	{
		return [
			['title', 'text'],
			['url', 'text'],
			['slug', 'slug', 'source' => 'title'],
			['body', 'tinymce_small'],
			['logo', 'image', 'module' => 'image'],
			['home', 'toggle'],
			['vtimes', 'toggle'],
			['seo','seo',
				'source' => [
					'title' => 'title',
					'slug' => 'slug',
					'body' => ['intro', 'body']
				],
				'default' => ['title' => '[title] - [website]']
			],
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
				'logo',
				'title',
				'url',
				'body',
			]],

			['column', [
				['section', [
					'slug'
				]],
				'home',
				'vtimes',
				['related', 'id' => $this->id],
				'seo',
			], 'width' => 3],
		];
	}
}