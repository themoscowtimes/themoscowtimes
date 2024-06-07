<?php return [
	'name' => 'Advertorials',
	'article' => 'the',
	'single' => 'Advertorial',
	'plural' => 'Advertorials',
	'new' => 'new',
	'description' => 'Advertorials management',
	'label' => [
		'create' => 'new',
	],
	'field' => [
		'slug' => 'Manual url',
		'title' => 'Headline',
		'subtitle' => 'Subheader',
		'intro' => 'Introduction text',
		'excerpt' => 'Preview text',
		'authors' => 'author(s)',
		'partners' => 'Editorial Partner(s)',
		'time_publication' => 'date/time',
		'video' => 'Youtube url',
		'audio' => 'Podcast embed',
		'type' => 'Type',
		'keyword' => 'Label above headline',
		'position' => 'position',
		'source' => 'source',
		'image' => 'Main image'
	],
	'option' =>[
		'type' => [
			'default' => 'article',
			'video' => 'video',
			'gallery' => 'gallery',
			'podcast' => 'podcast',
		],
		'position' => [
			'column' => 'Width of the text column',
			'full' => 'Text column and sidebar',
			'outside' => 'In sidebar',
			'left' => 'In the text, align left',
			'right' => 'In the text, align right',
		]
	]
];