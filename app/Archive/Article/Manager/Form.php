<?php

namespace Archive\Article\Manager;

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
			['keyword', 'text'],

			['image', 'image', 'module' => 'archiveimage'],
			['caption', 'text'],
			['credits', 'text'],
			['source', 'text'],

			['excerpt', 'textarea'],
			//['section', 'select', 'options' => ['news', 'opinion', 'meanwhile', 'city', 'business', 'russian', 'indepth', 'other']],
			['type', 'select', 'options' => ['default', 'video', 'gallery', 'podcast']],

			['body', 'blocks', 'types' => ['html', 'header', 'image', 'images','article', 'quote', 'aside', 'embed', 'link'], 'autoselect' => true, 'default' => [['type' => 'html', 'body' => '']]],
			['video', 'text'],
			['audio', 'textarea'],
			['images', 'image', 'multiple' => true,  'module' => 'archiveimage',  'junction' => [['title', 'text'], ['caption', 'textarea'], ['credits', 'text']]],

			['authors', 'relation', 'multiple' => true, 'module' => 'archiveauthor', 'view' => 'author'],
			['slug', 'slug', 'source' => 'title'],
			['time_publication', 'time_publication', 'timezone' => 'Europe/Moscow'],

			//['tags', 'tag'],
			//['articles', 'relation', 'multiple' => true, 'module' => 'article', 'view' => 'article'],

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
						'authors'
					]],
					['section', [
						['column', ['image']],
						['column', ['caption', 'credits']],
					]],
					'video',
					'audio',
					'body',
					'images',
				]]
			]],

			['column', [
				['section', [
					'slug',
				]],
				//'tags',
				//'articles',
				'seo',
			], 'width' => 3],
		];
	}
}