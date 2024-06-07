<?php return [
	'filesystem' => [
		'origin' => 'images',
		'cache' => 'cache'
	],
	'presets' => [
		'manager' => ['width' => 100, 'height' => 100, 'quality' => 90],

		'thumb' => ['width' => 100, 'height' => 100, 'crop' => true,  'quality' => 50],

		'og' => ['width' =>  1360, 'quality' => 500],
		'120' => ['width' =>  120, 'quality' => 90],
		'320' => ['width' =>  320, 'quality' => 90],
		'640' => ['width' =>  640, 'quality' => 90],
		'960' => ['width' =>  960, 'quality' => 90],
		'1360' => ['width' =>  1360, 'quality' => 90],
		'1920' => ['width' =>  1920, 'quality' => 90],


		'article_640' => ['width' =>  640, 'height' => 360, 'crop' => true, 'quality' => 40, 'enlarge' => true],
		'article_640-amp' => ['width' =>  640, 'height' => 360, 'crop' => true, 'quality' => 90, 'enlarge' => true],
		'article_960' => ['width' =>  960, 'height' => 540, 'crop' => true, 'quality' => 90, 'enlarge' => true],
		'article_1360' => ['width' =>  1360, 'height' => 765, 'crop' => true, 'quality' => 90, 'enlarge' => true],
		'article_1920' => ['width' =>  1920, 'height' => 1080, 'crop' => true, 'quality' => 90, 'enlarge' => true],
		
		'liveblog_4x1' => ['width' =>  4, 'height' => 1, 'crop' => true, 'quality' => 90, 'enlarge' => true],

		
		'original' => ['width' => 20000, 'height' => 20000, 'crop' => false, 'quality' => 100, 'enlarge' => false]
	]
];