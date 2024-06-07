<?php
$tags = [];
foreach($item->tags as $tag) {
	$tags[] = $tag->title;
}
$archive = $archive ?? false;
$website = 'The Moscow Times';
$logo = fetch::url('static') . 'img/logo.png';
$url = $archive ? fetch::route('archive_article', $item->data()) : fetch::route('article', $item->data());
$title = isset($item->seo['title']) ? str_replace(['[website]', '[title]'], [$website, $item->title], $item->seo['title']) : $item->title;
$twitterTitle = $item->title;

$paragraph = '';
if (is_array($item->body)) {
	foreach ($item->body as $block) {
		if($block['type'] === 'html') {
			$paragraph = fetch::truncate( preg_replace( "/\r|\n/", " ", strip_tags(html_entity_decode($block['body']))), 2000 );
			break;
		}
	}
}

$sentences = preg_split('~([a-zA-Z]([.?!]"?|"?[.?!]))\K\s+(?=[A-Z"])~', $paragraph);
$description = count($sentences) >= 0 ? $sentences[0] : '';

// $description = ($item->seo['description'] ?? '') ?: ($item->excerpt ?: $item->intro);

$descripton_pre = '';
$sectionSlug = 'news';
$sectionLabel = 'News';

if ($item['business']) {
	$sectionSlug = 'business';
	$sectionLabel = fetch::lang('section.business');
} elseif ($item['climate']) {
	$sectionSlug = 'climate';
	$sectionLabel = fetch::lang('section.climate');
} elseif ($item['opinion']) {
	$sectionSlug = 'opinion';
	$sectionLabel = fetch::lang('section.opinion');
} elseif ($item['meanwhile']) {
	$sectionSlug = 'meanwhile';
	$sectionLabel = fetch::lang('section.meanwhile');
} elseif ($item['city']) {
	$sectionSlug = 'arts-and-life';
	$sectionLabel = fetch::lang('section.city');
} elseif ($item['indepth']) {
	$sectionSlug = 'in-depth';
	$sectionLabel = fetch::lang('section.indepth');
} elseif ($item['diaspora']) {
	$sectionSlug = 'diaspora';
	$sectionLabel = fetch::lang('section.diaspora');
} elseif ($item['ukraine_war']) {
	$sectionSlug = 'ukraine-war';
	$sectionLabel = fetch::lang('section.ukraine_war');
} elseif ($item['lecture_series']) {
	$sectionSlug = 'lecture-series';
	$sectionLabel = fetch::lang('section.ecture_series');
}




if ($item->opinion) {
	$descripton_pre = 'Opinion | ';
} else {
	switch ($item->type) {
    case 'video':
		$descripton_pre = 'Video | ';
        break;
    case 'gallery':
		$descripton_pre = 'Gallery | ';
		break;
	case 'podcast':
		$descripton_pre = 'Podcast | ';
		break;
	}
}
$description = $descripton_pre . $description;
$keywords = $item->seo['keyword'] ?? implode(',', $tags);
$thumb = $item->image ? fetch::src($item->image, '320') :  fetch::url('static') . 'img/thumb_default.jpg';

$image = $item->image ? fetch::src($item->image, '1360', $archive) :  fetch::url('static') . 'img/share_default.jpg';

if($item->type == 'video' && $item->video) {
	$regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
	if(preg_match($regex, $item->video, $matches)) {
		$image = 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
	}
}

$width = 1360;
$height = $item->image ? round(1360  / ($item->image->width > 0 ? $item->image->width : 1)) * $item->image->height : 500;


$image = $archive ? $image : fetch::src($item->id, 'og');
$width = 1200;
$height = 630;
?>

<?php view::file('seo/title', ['title' => $title]) ?>

<?php view::file('seo/link', ['link' => [
	'canonical' => $url
]]) ?>

<?php view::file('seo/meta', ['meta' => [
	'keywords' => $keywords,
	'news_keywords' => $keywords,
	'description' => $description,
	'thumbnail' => $thumb,
	'author' => count($item->authors) > 0 ? $item->authors[0]->title : 'The Moscow Times',
]]); ?>

<?php view::file('seo/properties', ['properties' => [
	'og:url' => $url,
	'og:title' => trim($item->title_long) ? trim($item->title_long) : $title,
	'og:description' => $description,
	'og:image' => $image,
	'og:image:width' => $width,
	'og:image:height' => $height,


	'article:author' => count($item->authors) > 0 ? $item->authors[0]->title : 'The Moscow Times',
	'article:content_tier' => 'free',
	'article:modified_time' => date('Y-m-d\TH:i:sP' , strtotime($item->timestamp)),
	'article:published_time' => date('Y-m-d\TH:i:sP' , strtotime($item->time_publication)),
	'article:publisher' => 'https://www.facebook.com/MoscowTimes',
	'article:section' => fetch::section($item),
	'article:tag' => implode(',', $tags),

	'twitter:title' => trim($item->title_long) ? trim($item->title_long) : $twitterTitle,
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
		],
		[
			"@type" => "ListItem",
			"position" => 2,
			"name" => $sectionLabel,
			"item" => fetch::url('base') . $sectionSlug
		],
		[
			"@type" => "ListItem",
			"position" => 3,
			"name" => $item['title'],
			"item" => $url
		]
	]
];

?>

<?php

$json = [
	'@context' => 'http://schema.org/',
	'@type' => 'NewsArticle',
	'dateCreated' => date('Y-m-d\TH:i:sP' , strtotime($item->created)),
	'datePublished' =>date('Y-m-d\TH:i:sP' , strtotime($item->time_publication)),
	'dateModified' => date('Y-m-d\TH:i:sP' , strtotime($item->timestamp)),
	'name' => $item->title,
	'headline' => $item->title,
	'description' => $description,
	'keywords' => $keywords,
	'articleSection' => fetch::section($item),
	'isAccessibleForFree' => true,
	'mainEntityOfPage' => $url,
	'url' => $url,
	'thumbnailUrl' => $thumb,
	'image' => [
		'@type' => 'ImageObject',
		'url' => $image,
		'width' => $width,
		'height' => $height,

	],
	'publisher' => [
		'@type' => 'Organization',
		'name' => $website,
		'logo' => [
			'@type' => 'ImageObject',
			'url' => $logo,
			'width' => 50,
			'height' => 50,
		],
	],
	'inLanguage' => [
		'@type' => 'Language',
		'name' => 'English',
		'alternateName' => 'English',
	]
];


if($item->issue) {
	$json['printEdition'] =  $item->issue->number;
}

if($item->use_time_end) {
	$json['expires'] =  date('Y-m-d\TH:i:sP' , strtotime($item->time_end));
}

if (count($item->authors) > 0) {
	$json['creator'] = $item->authors[0]->title;
	$json['author'] = [
		'@type' => 'Person',
		'name' => $item->authors[0]->title,
		'description' => $item->authors[0]->description,
		'image' => $item->authors[0]->image ? fetch::src($item->authors[0]->image, 320) : fetch::url('static') . 'avatar_default.jpg',
		'url' => fetch::route('author', ['slug' => $item->authors[0]->slug])
	];

	if($item->authors[0]->twitter) {
		$json['author']['sameAs'] = 'http://www.twitter.com/' . trim($item->authors[0]->twitter, '@');
	}
} else {
	$json['author'] = [
		'@type' => 'Organization',
		'name' => 'The Moscow Times'
	];
}

// TODO: only render if article is available in AMP format
// link to amp version of article
//$amp_url = fetch::url('base') .'all/' . $item->id . '?amp=1';
//echo("<link rel=\"amphtml\" href=\"" . $amp_url . "\"" . ">" ."\r\n");

if($item->sponsored) {
	/*
			"sponsor": { //MT+ artikelen
            "@type": "Organization",
            "name": "%%adverteerder%%",
			"url": "http://www.example.com/",
            logo": {
				"height": 100,
				"@type": "ImageObject",
				"url": "",
				"width": 100
			}
        }
	 */
}

if($item->type == 'audio') {
/*
	"audio": {
            "@type": "AudioObject",
			"embedUrl": "SRC of the embed"
        },
*/
}

if($item->type == 'video') {
/*
	 "video": {
            "@type": "VideoObject",
			"thumbnail": "foo-fighters-interview-thumb.jpg",
			"thumbnailUrl": "name of video VERPLICHT",
			"embedUrl": "SRC of the embed",
			"description": "description of video VERPLICHT",
			"name": "name of video VERPLICHT",
			"uploadDate": "name of video VERPLICHT"
        },
*/
}

view::file('seo/json', ['json' => $json]);
view::file('seo/json', ['json' => $json_breadcrumbs]); ?>