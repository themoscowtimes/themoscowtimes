<?php

$env = require __DIR__ . '/env.php';

return [
	'paths' =>[
		'migrations'=> __DIR__ . '/../storage/migrations',
		'seeds'=>  __DIR__ . '/../storage/seeds'
	],
	'environments'=> [
		'default_migration_table'=> 'phinxlog',
		'default_database'=> 'default',
		'default'=> [
			'adapter'=> 'mysql',
			'host'=> $env['mysql.host'],
			'name'=> $env['mysql.database'],
			'user'=> $env['mysql.username'],
			'pass'=> $env['mysql.password'],
			'port' => '3306',
			'charset'=> 'utf8'
		]
	]
];