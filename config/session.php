<?php return [
	'name' => 'sessionid',
	'storage' => [
		'type' => '{{session.storage.type}}',
		'database' => [
			'dsn' => 'mysql:host={{mysql.host}};dbname={{mysql.database}}',
			'username' => '{{mysql.username}}',
			'password' => '{{mysql.password}}',
			'options' => [
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
			]
		],
		'redis' => [
			'host' => '{{cache.host}}',
			'port' => '{{cache.port}}',
			'auth' => '{{cache.auth}}'
		]
	]
];