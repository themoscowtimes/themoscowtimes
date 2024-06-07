<?php
return [
	'paths' => [
		__DIR__ . '/../resources/views/'
	],
	'environment' => [
		'cache' => __DIR__ . '/../storage/cache/twig',
		'debug' => true

	],
	'extensions' => [
		'View\Extension'
	]
];