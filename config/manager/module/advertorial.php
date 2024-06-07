<?php return [
	'entity' => 'Advertorial\Entity',
	'with' => ['author:image'],
	'manager' => 'Advertorial\Manager\Manager',
	'form' => 'Advertorial\Manager\Form',
	'route' => 'advertorial',
	'preview' => 'advertorialpreview',
	'publish' => true,
	'lock' => false,
	'filters' => [
		'status' => ['edit', 'live'],
		'type' => ['default',  'gallery', 'video'],
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
		'type'
	],
];