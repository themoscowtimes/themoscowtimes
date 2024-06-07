<?php

namespace Home\Manager;

use Sulfur\Form\Builder;

class Form extends Builder
{
	public function attributes()
	{

	}


	public function elements()
	{
		return [
			['highlights', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => true, 'order' => 'desc'],
			['today', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => true, 'order' => 'desc'],

			['dossier_1', 'relation', 'module' => 'dossier', 'view' => 'title',],
			['dossier_1_location', 'select', 'options' => [1, 2, 3]],

			['article_live', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],

			['dossier_2', 'relation', 'module' => 'dossier', 'view' => 'title',],
			['dossier_2_location', 'select', 'options' => [1, 2, 3]],

			['dossier_3', 'relation', 'module' => 'dossier', 'view' => 'title',],
			['dossier_3_location', 'select', 'options' => [1, 2, 3]],

			// ['article_feature', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
			// ['article_pick', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],

			['article_opinion', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
			['article_opinion_end', 'date', 'time'=> true],

			['article_meanwhile', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
			['article_meanwhile_end', 'date', 'time'=> true],

			['article_business', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
			['article_business_end', 'date', 'time'=> true],

			['article_city', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
			['article_city_end', 'date', 'time'=> true],

			['article_indepth', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
			['article_indepth_end', 'date', 'time'=> true],

			['article_living', 'relation', 'module' => 'article', 'view' => 'article', 'multiple' => false, 'order' => 'desc'],
			['article_living_end', 'date', 'time'=> true],
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
			['home', [
				['section', [
					'dossier_1',
					'dossier_1_location',
				]],
				'highlights',
				'article_live',
				['section', [
					'dossier_2',
					'dossier_2_location',
				]],
				['section', [
					'dossier_3',
					'dossier_3_location',
				]],
				'today',
				// 'highlights',
				// ['section', [
				// 	'article_feature',
				// 	'article_pick',
				// ]],
				['section', [
					'article_opinion',
					'article_opinion_end',
				]],
				['section', [
					'article_meanwhile',
					'article_meanwhile_end',
				]],
				['section', [
					'article_business',
					'article_business_end',
				]],
				['section', [
					'article_city',
					'article_city_end',
				]],
				['section', [
					'article_indepth',
					'article_indepth_end',
				]]
			]],
		];
	}
}