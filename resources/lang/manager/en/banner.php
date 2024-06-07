<?php return [
	'name' => 'Banners',
	'article' => 'the',
	'single' => 'banner',
	'plural' => 'banners',
	'new' => 'new',
	'description' => 'Banner management',
	'field' => [
		'url' => 'Target url',
		'type' => 'banner type',
		'html' => 'Script-tag for external banners',
		'positions' => 'Show banner on positions',
	],
	'option' => [
		'type' => [
			'image' => 'Image banner',
			'tag' => 'External script-tag',
		],
		'positions' => [
			'home_top' => 'Homepage above website',
			'home_1' => 'homepage news sidebar first position',
			'home_1_mobile' => 'Homepage above Opinion',
			'home_2' => 'homepage news sidebar second position',
			'home_3' => 'homepage billboard above opinion',
			'home_3_mobile' => 'homepage before Photos & Videos',
			'home_4' => 'homepage sidebar section opinion',
			'home_5' => 'homepage billboard after meanwhile',
			'home_5_mobile' => 'homepage after feature',

			'article_top' => 'Article above website',
			'article_1' => 'Article sidebar',
			'article_sidebar_bottom' => 'Article sidebar at bottom',
			'article_2' => 'Article billboard bottom',
			'article_2_mobile' => 'Article billboard bottom',
			'article_body' => 'Article in article',
			'article_body_mobile' => 'Article in article',
			'article_body_amp' => 'Article in content AMP',

			'sticky_article_billboard_bottom' => 'Sticky Article Billboard Bottom',

			'section_top' => 'Articles listing above website',
			'section_1' => 'Article listing sidebar',

			'event_top' => 'Event above website',
			'event_1' => 'Event sidebar',
			'event_2' => 'Event billboard bottom',
			'event_2_mobile' => 'Event billboard bottom',

			'advertorial_top' => 'MT+ above website',
			'advertorial_1' => 'MT+ sidebar',
			'advertorial_2' => 'MT+ billboard bottom',
			'advertorial_2_mobile' => 'MT+ billboard bottom',

			'other_top' => 'Other pages above website',
			'other_1' => 'Other pages sidebar',
			'other_2' => 'Other pages billboard bottom',
			'other_2_mobile' => 'Other pages billboard bottom',
		],
	]
];
