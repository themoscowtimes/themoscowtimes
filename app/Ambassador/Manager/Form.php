<?php

namespace Ambassador\Manager;

use Sulfur\Form\Builder;

class Form extends Builder
{

	public function elements()
	{
		return [
			['title', 'text'],
			['subtitle', 'text'],
			['intro', 'textarea'],
			['slug', 'slug', 'source' => 'title'],
			['body', 'tinymce'],
			['image', 'image', 'module' => 'image'],
			// ['files', 'relation', 'module' => 'file', 'multiple' => true, 'junction' => [['title','text']], 'view' => 'file'],
			// ['caption', 'text'],
			// ['credits', 'text'],
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
				['line', ['title', 'subtitle']],
				'intro',
				['section', [
					['column', ['image']],
					// ['column', ['caption', 'credits']],
				]],
				'body',
				// 'files'
			]],

			['column', [
				['section', [
					'slug'
				]],
				'seo',
			], 'width' => 3],
		];
	}
}