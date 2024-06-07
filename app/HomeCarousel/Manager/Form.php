<?php

namespace HomeCarousel\Manager;

use Sulfur\Form\Builder;

class Form extends Builder
{

	public function elements()
	{
		return [
      ['title', 'text', 'default' => "Editor's Pick"],
      ['article', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
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
        ['line', ['title']],
        'article'
			]]
		];
	}
}