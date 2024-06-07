<?php

namespace Event\Manager;

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
			['subtitle', 'text'],
			['body', 'tinymce'],
			['type', 'select', 'options' => ['theater', 'movie']],
			['time', 'date', 'time' => true],
			['show_time_end', 'toggle'],
			['time_end', 'date', 'time' => true],
			['show_time', 'toggle'],
			['image', 'image'],
			['locations', 'relation', 'multiple' => true, 'module' => 'location', 'view'=> 'location', 'junction' => [ ['time', 'text'], ['info', 'text']]],
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
				'title', 'subtitle','image',
			]],
			['column', [
				 'body'
			]],
			['column', [
				['section', [
					'slug',
					'time',
					'show_time_end',
					'time_end',
					'show_time',
					'type',
					'locations'
				]],
			]],
		];
	}
}