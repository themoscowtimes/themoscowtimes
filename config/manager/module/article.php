<?php return [
	'entity' => 'Article\Entity',
	'with' => ['author:image'],
	'manager' => 'Article\Manager\Manager',
	'form' => 'Article\Manager\Form',
	'route' => 'article',
	'preview' => 'articlepreview',
	'publish' => true,
	'lock' => true,
	'toggle' => [],
	'filters' => [
		'status' => ['edit', 'live'],
		'section' => ['news', 'ukraine_war', 'opinion', 'city', 'meanwhile', 'business', 'climate', 'diaspora', 'sponsored', 'russian', 'analysis', 'indepth', 'lecture_series'],
		'type' => ['default',  'gallery', 'podcast', 'video', 'live'],
	],
	'sort' => [
		'time_publication' => 'DESC',
	],
	'columns' => [
		'status' => [
			'width' => 30,
			'view' => 'status'
		],
		'time_publication' => [
			'width' => 200,
			'view' => 'time_publication'
		],
		'title',
		'section' => [
			'view' => 'section'
		],
		'type'=> [
			'view' => 'type'
		],
		'telegram' => [
			'view' => 'telegram'
		],
	],
	'actions' => [
		'preview' => [
			'allowed' => true,
			'view' => 'preview'
		]
	]
];