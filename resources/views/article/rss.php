<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
		<title>The Moscow Times - Independent News From Russia</title>
		<link><?php view::route($section)?></link>
		<description>The Moscow Times offers everything you need to know about Russia: Breaking news, top stories, business,
			analysis, opinion, multimedia</description>
		<atom:link rel="self" href="<?php view::route('rss', ['section' => $section])?>"></atom:link>
		<language>en-us</language>
		<lastBuildDate><?php view::text(date('r')) ?></lastBuildDate>
		<ttl>600</ttl>
		<?php foreach($items as $item): ?>
		<item>
			<title><?php view::text($item->title); ?></title>
			<link><?php view::route('article', $item->data()); ?></link>
			<description>
				<?php if ($item->type == 'live'): ?>
				<?php foreach ($item->excerpt_live as $excerpt): ?>
				<![CDATA[<?php view::text($excerpt . '.'); ?>]]>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php view::text($item->excerpt ? $item->excerpt : ($item->intro ? $item->intro : $item->subtitle)); ?>
			</description>
			<pubDate><?php view::text(date('r', strtotime($item->time_publication))) ?></pubDate>
			<guid><?php view::route('article', $item->data()); ?></guid>
		</item>
		<?php endforeach; ?>
	</channel>
</rss>