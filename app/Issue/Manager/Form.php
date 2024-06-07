<?php

namespace Issue\Manager;

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
			['number', 'text'],
			['intro', 'textarea'],
			['date', 'date'],
			['image', 'image'],
			['file', 'file'],
			['articles', 'relation', 'multiple' => true,  'module' => 'article', 'view' => 'article'],
			['status', 'status'],
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
				'title','number','intro','image'
			]],
			['column', [
				'file', 'articles',
			]],
			['column', [
				['section', [
					'status',
					'date',
				]],
			]],
		];
	}
}