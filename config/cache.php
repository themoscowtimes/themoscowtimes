<?php return [
	'default' => [
		'storage' => [
			'type' => Sulfur\Cache::STORAGE_REDIS,
			'host' => '{{cache.host}}',
			'port' => 6379,
			'auth' => '{{cache.auth}}',
		],
		'active' => '{{cache.active}}',
		'lifetime' => 3600
	],
	'file' => [
		'storage' => [
			'type' => Sulfur\Cache::STORAGE_FILE,
			'path' => '{{cache.path}}'
		],
		'active' => '{{cache.active}}',
		'lifetime' => 3600
	],
];