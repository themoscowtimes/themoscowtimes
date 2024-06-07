<?php

$archive = $archive ?? false;

$authorTags = [];
foreach ($item->authors as $author) {
	if (!$archive) {
		$authorTags[] = '<a href="' . fetch::route('author', ['slug' => $author->slug]) . '" class="byline__author__name" title="' . fetch::attr($author->title) . '">' . fetch::text($author->title) . '</a>';
	} else {
		$authorTags[] = '<span class="byline__author__name">' . fetch::text($author->title) . '</span>';
	}
}
$authorHtml = '';
$separator = $item->type == 'podcast' ? '' : 'By ';
while ($authorTag = array_shift($authorTags)) {
	$authorHtml .= $separator . $authorTag;
	if (count($authorTags) > 1) {
		$separator = ', ';
	} else {
		$separator = ' and ';
	}
}

$body = '';
foreach ($item->body as $block) {
	if ($block['type'] == 'html') {
		$body .= $block['body'];
	} elseif ($block['type'] == 'image') {
		//$body .=
			//'<img src="'. $block['image']['src'].'"><br>' .
			//'<p style="font-family: roboto;">' . $block['image']['junction']['caption'] . ' <b>' . $block['image']['junction']['caption'] . '</b></p>';
	} elseif ($block['type'] == 'images') {
		// $body .= '<br><br><div>Image Gallery:<br><br>';
		foreach ($block['images'] as $gallery) {
			//$body .=
			//'<img src="'. $gallery['src'].'" height="200"> ' .
			//'<p style="font-family: roboto;">' . $gallery['junction']['caption'] . ' <b>' . $gallery['junction']['caption'] . '</b></p>';
		}
		$body .= '</div>';
	} elseif ($block['type'] == 'article') {
		$body .= '<br><b>Related article</b>: <a href="'. fetch::route('article', ['slug' => $block['article']['slug']]) .'">'. $block['article']['title'] .'</a>';
	} elseif ($block['type'] == 'quote') {
		$body .= '<br><br>"<q>'. $block['body'] .'</q>"<br><br>';
	} elseif ($block['type'] == 'embed') {
		$body .= '<br><b>Embed:<b> ' . $block['embed'] . '<br>';
	} elseif ($block['type'] == 'link') {
		$body .= '<br><b>Link:</b> <a href="'. $block['link']['url'] .'">'. $block['link']['url'] .'</a><br>';
	}
}

$output =
	'<img src="'. fetch::url('static') . 'img/logo_tmt_30_yo.svg' .'" width="410"><br><br>' .
	($item->title ? '<h1 style="font-family: roboto; font-variant: bold;">' . $item->title . '</h1>' : '') .
	($item->subtitle ? '<h2 style="font-family: roboto;">' . $item->subtitle . '</h2>' : '') .
	($authorHtml ? '<p style="font-family: roboto;">' . $authorHtml . '</p>' : '') .
	($item->time_publication ? '<p style="font-family: roboto;">' . date('F d, Y', strtotime($item->time_publication)) . '</p>' : '') .
	// ($item->updated ? '<p style="font-family: roboto;">Updated: ' . $item->updated . '</p>' : '') .
	($item->intro ? '<p>' . $item->intro . '</p>' : '') .
	'<img src="'. ($item->image ? fetch::src($item->image, 'article_1360') : fetch::url('static') . 'img/article_default.jpg') . '">' .
	(($item->caption || $item->credits) ? 
		'<p style="font-family: roboto;">' . $item->caption . ' <b>' . $item->credits. '</b></p>' : '')
	. $body .
	($item->opinion ? '<p style="font-style: italic;">The views expressed in opinion pieces do not necessarily reflect the position of The Moscow Times.</p>' : '') .
	'<p style="font-family: roboto;">Original url: ' . fetch::route('article', $item->data()) . '</p>';

view::raw($output);

?>
