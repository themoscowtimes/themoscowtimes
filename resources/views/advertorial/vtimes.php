<?php
$result = [];
foreach($items as $item) {
	$result[] = [
		'campaign_title' => $item->campaign ?  $item->campaign->title : '',
		'campaign_url' => fetch::route('campaign', ['slug' => $item->campaign ?  $item->campaign->slug : '-']),
		'campaign_logo' => $item->campaign && $item->campaign->logo ? fetch::src($item->campaign->logo) : null,
		'article_url' => fetch::route('advertorial', ['slug' => $item->slug, 'campaign' => $item->campaign ? $item->campaign->slug : '-']),
		'title' =>  $item->title,
		'subtitle' => $item->subtitle,
		'image' => $item->image ? fetch::src($item->image) : null,
	];
}
view::raw(json_encode($result, JSON_PRETTY_PRINT));
?>
