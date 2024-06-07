<?php return [
	'default' => [
		'type' => Sulfur\Database::TYPE_MYSQL,
		'dsn' => 'mysql:host={{mysql.host}};dbname={{mysql.database}}',
		'username' => '{{mysql.username}}',
		'password' => '{{mysql.password}}',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
		],
		'log' => '{{mysql.log}}'
	],

	'old' => [
		'type' => Sulfur\Database::TYPE_MYSQL,
		'dsn' => 'mysql:host={{mysql.host}};dbname=tmt_old',
		'username' => '{{mysql.username}}',
		'password' => '{{mysql.password}}',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
		],
		'log' => '{{mysql.log}}'
	],
];