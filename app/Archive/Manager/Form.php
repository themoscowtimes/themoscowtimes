<?php

namespace Archive\Manager;

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
			//['image', 'image', 'module' => 'image'],
			['images', 'image', 'multiple' => true],
			['articles', 'blocks', 'types' => ['article' => ['position' => false], 'archive' => ['position' => false]]],
			['issues', 'relation', 'module' => 'issue', 'max' => 4, 'multiple' => true],
			['seo','seo',
				'source' => [
					'title' => 'title',
					'slug' => 'slug',
					'body' => []
				],
				'default' => ['title' => '[title] - [website]']
			],
		];
	}

	public function rules()
	{
		return [];
	}

	public function processors()
	{
		return [];
	}


	public function layout()
	{
		return [
			['column', [
				'images',
				'articles',
				'issues',
			]],

			['column', [
				'seo',
			], 'width' => 3],
		];
	}
}