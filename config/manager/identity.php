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

	'acl' => [
		'staff' => [
			'main/zone' => ['access'],
			'manager' => ['access'],
			'start' => ['access', 'forward', 'index'],
			'article' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status', 'lock', 'locked'],
			'author' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'tag' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'purge'],
			'issue' => ['access', 'index', 'items'],
			'link' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'build'],
			'image' => ['access', 'index', 'items', 'create','update', 'save', 'delete', 'serve', 'crop', 'embed'],
			'preferences' => ['access', 'index', 'update', 'save'],
			'archivearticle' => ['access', 'index', 'items', 'preview', 'status'],
			'archiveauthor' => ['access', 'index', 'items', 'preview', 'status'],
			'archiveimage' => ['access', 'index', 'items', 'preview', 'status'],

			'dossier' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
		],

		'manager' => [
			['staff'],
			'preferences' => ['access', 'index', 'update', 'save'],
			'home' => ['access', 'index', 'update', 'save', 'preview'],

			//'footer' => ['access', 'index', 'update', 'save'],
			'article' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'author' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'partner' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'dossier' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'page' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'tag' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete'],
			'event' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'issue' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'archivehome' => ['access', 'index', 'create', 'update', 'save', 'preview'],
			'archivearticle' => ['access', 'index', 'items', 'update', 'save'],
			'archiveauthor' => ['access', 'index', 'items', 'update', 'save', 'status'],
			'archiveimage' => ['access', 'index', 'items', 'create','update', 'save', 'serve', 'crop', 'embed'],
			'live' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status', 'manage', 'posts', 'createpost', 'updatepost', 'deletepost'],
			'ambassador' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'campaign' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status', 'advertorials', 'advertorial'],
			'advertorial' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'home_carousel' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status', 'move'],
			'telegram_russian' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status', 'move'],
			'location' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'banner' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			'contribute' => ['access', 'index'],
			//'slide' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status'],
			//'form' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete'],
			'menu' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'status', 'move'],
			'link' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'build'],
			'image' => ['access', 'index', 'items', 'create','update', 'save', 'delete', 'serve', 'crop', 'embed'],
			'file' => ['access', 'index', 'items', 'create', 'update', 'save', 'delete', 'serve'],
			'settings' => ['access', 'index', 'update', 'save'],
		],

		'admin' => [
			['manager'],
			'user' => ['access', 'index', 'items', 'create', 'update', 'save','delete', 'roles'],
		],
	]
];