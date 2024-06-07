<?php

namespace Article\Manager;

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
			['title', 'textarea'],
			['title_live', 'toggle'],
			['title_long', 'text'],
			['subtitle', 'text'],
			['intro', 'textarea'],
			['keyword', 'text'],

			['image', 'image', 'module' => 'image'],
			['caption', 'text'],
			['credits', 'text'],
			['source', 'text'],

			['excerpt', 'textarea'],
			['excerpt_live', 'bullets', 'amount' => 3],
			['summary', 'tinymce_small'],
			['section', 'select', 'options' => ['news', 'opinion', 'meanwhile', 'city', 'business', 'russian', 'indepth', 'other']],
			['type', 'select', 'options' => ['default', 'video', 'gallery', 'podcast', 'live']],
			['news', 'toggle'],
			['opinion', 'toggle'],
			['meanwhile', 'toggle'],
			['city', 'toggle'],
			['business', 'toggle'],
			['climate', 'toggle'],
			['diaspora', 'toggle'],
			['ukraine_war', 'toggle'],
			['russian', 'toggle'],
			['indepth', 'toggle'],
			['lecture_series', 'toggle'],
			['sponsored', 'toggle'],
			['analysis', 'toggle'],

			['body', 'blocks', 'types' => ['html', 'header', 'image', 'images','article', 'quote', 'aside', 'embed', 'scrollama', 'link'], 'autoselect' => true, 'default' => [['type' => 'html', 'body' => '']]],
			['video', 'text'],
			['audio', 'textarea'],
			['images', 'image', 'multiple' => true,  'module' => 'image',  'junction' => [['title', 'text'], ['caption', 'textarea'], ['credits', 'text']]],

			['partners', 'relation', 'multiple' => true, 'module' => 'partner', 'view' => 'title'],
			['authors', 'relation', 'multiple' => true, 'module' => 'author', 'view' => 'author'],
			['slug', 'slug', 'source' => 'title'],
			['time_publication', 'time_publication', 'timezone' => 'Europe/Moscow'],

			['tags', 'tag'],
			['articles', 'relation', 'multiple' => true, 'module' => 'article', 'view' => 'article'],
			['issue', 'relation', 'max' => 1, 'module' => 'issue', 'view' => 'issue'],

			['dossiers', 'relation', 'multiple' => true, 'module' => 'dossier'],

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
						'title_live',
						'title_long',
						'subtitle',
						'intro',
						'time_publication',
					]],
					['column', [
						'type',
						'live',
						'excerpt_live',
						'summary',
						'keyword',
						'excerpt',
						'authors',
						'partners',
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
					['label', 'name' => 'sections'],
					'news',
					'ukraine_war',
					'opinion',
					'meanwhile',
					'city',
					'business',
					'climate',
					'diaspora',
					'russian',
					'indepth',
					'lecture_series',
					['label', 'name' => 'labels'],
					'sponsored',
					'analysis',
				]],
				'tags',
				'articles',
				'issue',
				'dossiers',
				'seo',
			], 'width' => 3],
		];
	}
}