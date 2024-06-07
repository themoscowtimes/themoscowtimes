<?php return [
	'manager' => 'Archive\Image\Manager\Manager',
	'form' => 'Sulfur\Manager\Image\Form',
	'entity' => 'Archive\Image\Entity',
	'route' => 'image_archive',
	'embed' => '320',
	'filters' => [],
	'toggle' => [],
	'size' => 25000000,
	'dimensions' => [
		'max' => ['width' => 6000, 'height' => 6000],
		'downsize' => ['width' => 2000, 'height' => 2000]
	],
	'path' => '',
];