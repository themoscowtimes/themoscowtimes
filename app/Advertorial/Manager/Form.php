<?php

namespace Advertorial\Manager;

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
			['subtitle', 'text'],
			['intro', 'textarea'],

			['image', 'image', 'module' => 'image'],
			['caption', 'text'],
			['credits', 'text'],
			['source', 'text'],

			['excerpt', 'textarea'],
			['type', 'select', 'options' => ['default', 'video']],

			['body', 'blocks', 'types' => ['html', 'header', 'image', 'images', 'quote', 'aside', 'embed', 'link'], 'autoselect' => true, 'default' => [['type' => 'html', 'body' => '']]],
			['video', 'text'],
			//['images', 'image', 'multiple' => true,  'module' => 'image',  'junction' => [['title', 'text'], ['caption', 'textarea'], ['credits', 'text']]],

			//['authors', 'relation', 'multiple' => true, 'module' => 'author', 'view' => 'author'],
			['slug', 'slug', 'source' => 'title'],
			['time_publication', 'time_publication', 'timezone' => 'Europe/Moscow'],

			['seo','seo',
				'source' => [
					'title' => 'title',
					'slug' => 'slug',
					'body' => ['subtitle', 'intro', 'image', 'body']
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
				['article', [
					['column', [
						'title',
						'subtitle',
						'intro',
						'time_publication',
					]],
					['column', [
						'type',
						'keyword',
						'excerpt',
						//'authors',
					]],
					['section', [
						['column', ['image']],
						['column', ['caption', 'credits']],
					]],
					'video',
					'body',
					'images',
				]]
			]],
			['column', [
				['section', [
					'slug',
				]],
				'seo',
			], 'width' => 3],
		];
	}
}