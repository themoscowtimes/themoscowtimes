<?php return [
	'login' => ['manager/login', 'Sulfur\Manager\Auth\Controller@login' ],
	'logout' => ['manager/logout', 'Sulfur\Manager\Auth\Controller@logout' ],
	'keepalive' => ['manager/keepalive', 'Sulfur\Manager\Auth\Controller@keepalive'],
	'start' => ['manager/(:zone)', 'Sulfur\Manager\Start\Controller@index'],
	'action' => ['manager/:zone/(:module)/(:action)/(:id)', 'Sulfur\Manager\Controller@action', 'module' => 'start', 'action' => 'index'],
];