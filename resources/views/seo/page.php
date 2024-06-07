<?php
$website = 'The Moscow Times';
$logo = fetch::url('static') . 'img/logo.png';
$url = fetch::route('page', $item->data());
$title = isset($item->seo['title']) ? str_replace(['[website]', '[title]'], [$website, $item->title], $item->seo['title']) : $item->title;
$description = ($item->seo['description'] ?? '') ?: ($item->subtitle ?: $item->intro);
$keywords = $item->seo['keyword'] ?? '';
$thumb = $item->image ? fetch::src($item->image, '320') :  fetch::url('static') . 'img/thumb_default.jpg';
$image = $item->image ? fetch::src($item->image, '1360') :  fetch::url('static') . 'img/share_default.jpg';
$width = 1360;
$height = $item->image ? round(1360  / ($item->image->width > 0 ? $item->image->width : 1)) * $item->image->height : 500;

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

<?php

view::file('seo/properties', ['properties' => [
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

$json = [
	'@context' => 'http://schema.org/',
	'@type' => 'NewsArticle',
	'dateCreated' => date('Y-m-d\TH:i:sP' , strtotime($item->created)),
	'dateModified' => date('Y-m-d\TH:i:sP' , strtotime($item->timestamp)),
	'name' => $item->title,
	'headline' => $item->title,
	'description' => $description,
	'keywords' => $keywords,
	'mainEntityOfPage' => $url,
	'url' => $url,
	'thumbnailUrl' => $thumb,
	'image' => [
		'@type' => 'ImageObject',
		'url' => $image,
		'width' => $width,
		'height' => $height,

	],
	'inLanguage' => [
		'@type' => 'Language',
		'name' => 'English',
		'alternateName' => 'English',
	]
];

view::file('seo/json', ['json' => $json]) ?>