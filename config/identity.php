<?php return [
	'entity' => 'Sulfur\Manager\User\Entity',
	'columns'=> [
		'identity' => 'username',
		'credentials' => 'password',
		'failed' => 'failed',
		'roles' => 'roles',
	],
	'throttle' => [
		'active' => true,
		'time' => 5 * 60,
		'attempts' => 3
	],
	'sessionkey' => '__identity__manager__',
	'acl' => []
];