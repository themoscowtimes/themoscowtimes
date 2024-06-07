<?php
$website = 'The Moscow Times';
$logo = fetch::url('static') . 'img/logo.png';
$url = fetch::route($section, ['tag' => $tag]);
$title = fetch::lang('section.' . $section) . ' - ' . $website;
$description = 'Independent news from Russia';
$keywords = 'news,russia,moscow,' . $section;
$thumb = fetch::url('static') . 'img/thumb_default.jpg';
$image = fetch::url('static') . 'img/share_default.jpg';
$width = 1360;
$height = 500;
?>

<?php view::file('seo/title', ['title' => $title]) ?>

<?php view::file('seo/link', ['link' => [
	'canonical' => $url
]]) ?>

<?php view::file('seo/meta', ['meta' => [
	'keywords' => $keywords,
	'description' => $description,
	'thumbnail' => $thumb,
]]); ?>

<?php view::file('seo/properties', ['properties' => [
	'og:url' => $url,
	'og:title' => $title,
	'og:description' => $description,
	'og:image' => $image,
	'og:image:width' => $width,
	'og:image:height' => $height,

	'twitter:description' => $description,
	'twitter:image:src' => $image,
]]); ?>

<?php
$sectionSlug = 'news';
$sectionLabel = 'News';

if ($section == 'business') {
	$sectionSlug = 'business';
	$sectionLabel = fetch::lang('section.business');
} elseif ($section == 'climate') {
	$sectionSlug = 'climate';
	$sectionLabel = fetch::lang('section.climate');
} elseif ($section == 'opinion') {
	$sectionSlug = 'opinion';
	$sectionLabel = fetch::lang('section.opinion');
} elseif ($section == 'meanwhile') {
	$sectionSlug = 'meanwhile';
	$sectionLabel = fetch::lang('section.meanwhile');
} elseif ($section == 'city') {
	$sectionSlug = 'arts-and-life';
	$sectionLabel = fetch::lang('section.city');
} elseif ($section == 'indepth') {
	$sectionSlug = 'in-depth';
	$sectionLabel = fetch::lang('section.indepth');
} elseif ($section == 'podcasts') {
	$sectionSlug = 'podcasts';
	$sectionLabel = fetch::lang('section.podcasts');
} elseif ($section == 'video') {
	$sectionSlug = 'videos';
	$sectionLabel = fetch::lang('section.video');
} elseif ($section == 'gallery') {
	$sectionSlug = 'galleries';
	$sectionLabel = fetch::lang('section.gallery');
} elseif ($section == 'diaspora') {
	$sectionSlug = 'diaspora';
	$sectionLabel = fetch::lang('section.diaspora');
} elseif ($section == 'ukraine_war') {
	$sectionSlug = 'ukraine-war';
	$sectionLabel = fetch::lang('section.ukraine_war');
} elseif ($section == 'lecture_series') {
	$sectionSlug = 'lecture-series';
	$sectionLabel = fetch::lang('section.lecture_series');
}

	$json_breadcrumbs = [
		'@context' => 'http://schema.org/',
		'@type' => 'BreadcrumbList',
		"itemListElement" => [
			[
				"@type" => "ListItem",
				"position" => 1,
				"name" => 'The Moscow Times',
				"item" => fetch::url('base')
			],
			[
				"@type" => "ListItem",
				"position" => 2,
				"name" => $sectionLabel,
				"item" => fetch::url('base') . $sectionSlug
			]
		]
	];

	view::file('seo/json', ['json' => $json_breadcrumbs]);
?>