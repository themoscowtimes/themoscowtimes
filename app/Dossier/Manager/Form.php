<?php

namespace Dossier\Manager;

use Sulfur\Form\Builder;

class Form extends Builder
{

	public function elements()
	{
		return [
			['title', 'text'],
			['subtitle', 'text'],
			['title_live', 'toggle'],
			['label', 'text', 'default' => 'Collection Label'],
			['intro', 'textarea'],
			['slug', 'slug', 'source' => 'title'],
			['body', 'tinymce_small'],
			['image', 'image', 'module' => 'image'],
			['caption', 'text'],
			['credits', 'text'],
			['video', 'text'],
			['articles', 'relation', 'module' => 'article', 'multiple' => true],
			['tags', 'tag'],
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
				'label',
				'title_live',
				'intro',
				['section', [
					'video',
					['column', ['image']],
					['column', ['caption', 'credits']],
				]],
				//'body',
				['line', ['tags', 'articles']],
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