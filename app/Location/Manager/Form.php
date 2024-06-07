<?php

namespace Location\Manager;

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
			['description', 'textarea'],
			['type', 'select', 'options' => ['theater', 'cinema', 'bar']],
			['open', 'text'],
			['metro', 'text'],
			['address', 'text'],
			['phone', 'text'],
			['website', 'text'],
			['twitter', 'text'],
			['facebook', 'text'],
			['image', 'image'],
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
				'title','description', 'image',
			]],
			['column', [
				'open', 'metro', 'address', 'phone', 'website', 'twitter', 'facebook'
			]],
			['column', [
				['section', [
					'slug',
					'status',
					'type',
				]],
			]],
		];
	}
}