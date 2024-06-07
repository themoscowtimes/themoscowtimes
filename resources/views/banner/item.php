<?php /*
xs: 0 - 768
sm: 769 - 992
md 992 - 1200
lg 1200 - 1600
xl 1600 - inf
*/ ?>

<?php
$positions = [
	'home_top' => [
		'template' => 'billboard',
		'viewports' => ['md','lg','xl'],
	],
	'home_1'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'home_1_mobile'=> [
		'template' => 'section',
		'viewports' => ['xs'],
	],
	'home_2'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'home_3'=> [
		'template' => 'wide',
		'viewports' => ['sm','md','lg','xl'],
	],
	'home_3_mobile'=> [
		'template' => 'wide',
		'viewports' => ['xs'],
	],
	'home_4'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'home_5'=> [
		'template' => 'wide_white',
		'viewports' => ['sm','md','lg','xl'],
	],
	'home_5_mobile'=> [
		'template' => 'wide_white',
		'viewports' => ['xs'],
	],
	'article_top'=> [
		'template' => 'billboard',
		'viewports' => ['md','lg','xl'],
	],
	'article_body'=> [
		'template' => 'content',
		'viewports' => ['sm', 'md','lg','xl'],
	],
	'article_body_mobile'=> [
		'template' => 'content',
		'viewports' => ['xs'],
	],
	'article_body_amp'=> [
		'template' => 'amp',
		'viewports' => ['sm', 'md','lg','xl'],
	],
	'article_1'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'article_2'=> [
		'template' => 'wide',
		'viewports' => ['sm','md','lg','xl'],
	],
	'sticky_article_billboard_bottom' => [
		'template' => 'sticky_bottom',
		'viewports' => ['md','lg','xl'],
	],
	'article_sidebar_bottom'=> [
		'template' => 'aside',
		'viewports' => ['sm','md','lg','xl'],
	],
	'article_2_mobile'=> [
		'template' => 'wide',
		'viewports' => ['xs'],
	],
	'section_top'=> [
		'template' => 'billboard',
		'viewports' => ['md','lg','xl'],
	],
	'section_1'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'event_top'=> [
		'template' => 'billboard',
		'viewports' => ['md','lg','xl'],
	],
	'event_1'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'event_2'=> [
		'template' => 'wide',
		'viewports' => ['sm','md','lg','xl'],
	],
	'event_2_mobile'=> [
		'template' => 'wide',
		'viewports' => ['xs'],
	],
	'advertorial_top'=> [
		'template' => 'billboard',
		'viewports' => ['md','lg','xl'],
	],
	'advertorial_1'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'advertorial_2'=> [
		'template' => 'wide',
		'viewports' => ['sm','md','lg','xl'],
	],
	'advertorial_2_mobile'=> [
		'template' => 'wide',
		'viewports' => ['xs'],
	],
	'other_top'=> [
		'template' => 'billboard',
		'viewports' => ['md','lg','xl'],
	],
	'other_1'=> [
		'template' => 'aside',
		'viewports' => ['md','lg','xl'],
	],
	'other_2'=> [
		'template' => 'wide',
		'viewports' => ['sm','md','lg','xl'],
	],
	'other_2_mobile'=> [
		'template' => 'wide',
		'viewports' => ['xs'],
	],
];
?>


<?php if (isset($positions[$position]) ): ?>
	<?php view::extend('banner/template/' . $positions[$position]['template'] ); ?>
	<?php view::start('banner') ?>
		<?php
		$pool = [];
		foreach($banners as $banner) {
			$pool[] = [
				'type' => $banner->type,
				'html' => $banner->html,
				'href' => isset($banner->link['url']) ? $banner->link['url'] : '#',
				'src' => $banner->image ? fetch::src($banner->image, 'original') : false,
			];
		}
		?>
		<div y-use="Banner" data-pool="<?php view::attr(json_encode($pool)) ?>" data-viewports="<?php view::attr(json_encode($positions[$position]['viewports'])) ?>"></div>
	<?php view::end(); ?>
<?php endif; ?>