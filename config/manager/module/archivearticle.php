<?php return [
	'entity' => 'Archive\Article\Entity',
	'with' => ['author:image'],
	'manager' => 'Archive\Article\Manager\Manager',
	'form' => 'Archive\Article\Manager\Form',
	'route' => 'archive',
	'preview' => 'archivepreview',
	'publish' => false,
	'lock' => false,
	'toggle' => [],
	'filters' => [

	],
	'sort' => [
		'time_publication' => 'DESC',
	],
	'columns' => [
		'time_publication' => [
			'width' => 200,
			'view' => 'time_publication'
		],
		'title',
	],
	'actions' => [
		'preview' => [
			'allowed' => true,
			'view' => 'archivepreview'
		]
	]
];