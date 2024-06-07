<?php return [
	'entity' => 'HomeCarousel\Entity',
	'manager' => 'HomeCarousel\Manager\Manager',
	'form' => 'HomeCarousel\Manager\Form',
	'route' => 'home_carousel',
	'sortable' => true,
	'sort' => ['rank' => 'asc'],
	'columns' => [
		'title',
		'article' => [
			'view' => 'article'
		],
		'created'
	],
	// 'preview' => 'pagepreview',
	'publish' => true,
];