<?php return [
	'filesystem' => [
		'origin' => 'images_archive',
		'cache' => 'cache_archive'
	],
	'presets' => [
		'manager' => ['width' => 100, 'height' => 100, 'quality' => 90],
		'1360' => ['width' =>  1360, 'quality' => 90],
		'article_640' => ['width' =>  640, 'height' => 360, 'crop' => true, 'quality' => 40, 'enlarge' => true],
		'article_960' => ['width' =>  960, 'height' => 540, 'crop' => true, 'quality' => 90, 'enlarge' => true],
		'article_1360' => ['width' =>  1360, 'height' => 765, 'crop' => true, 'quality' => 90, 'enlarge' => true],
		'article_1920' => ['width' =>  1920, 'height' => 1080, 'crop' => true, 'quality' => 90, 'enlarge' => true],
	]
];