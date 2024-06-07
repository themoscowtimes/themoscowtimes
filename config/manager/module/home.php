<?php return [
	'entity' => 'Home\Entity',
	'with' => ['today.images', 'highlights.images'],
	'manager' => 'Home\Manager\Manager',
	'form' => 'Home\Manager\Form',
	'route' => 'home',
	'preview' => 'homepreview',
];