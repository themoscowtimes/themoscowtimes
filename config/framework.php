<?php return [
	'cache' => [
		'active' => '{{ framework.cache.active }}',
		'class' => Sulfur\Cache\Framework::class,
		'path' => '{{ framework.cache.path }}'
	]
];