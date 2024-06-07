<?php
$website = 'The Moscow Times';
$logo = fetch::url('static') . 'img/logo.png';
$url = fetch::url('base');
$title = $website;
$description = 'Independent news from Russia';
$keywords = 'news,russia,moscow';
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
$json_breadcrumbs = [
	'@context' => 'http://schema.org/',
	'@type' => 'BreadcrumbList',
	"itemListElement" => [
		[
			"@type" => "ListItem",
			"position" => 1,
			"name" => 'The Moscow Times',
			"item" => fetch::url('base')
		]
	]
];
view::file('seo/json', ['json' => $json_breadcrumbs]);
?>