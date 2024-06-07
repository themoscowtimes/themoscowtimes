<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
		<title>The Moscow Times - Independent News From Russia</title>
		<link><?php view::url('base');?>rss/all</link>
		<description>The Moscow Times offers everything you need to know about Russia: Breaking news, top stories, business,
			analysis, opinion, multimedia</description>
		<atom:link rel="self" href="<?php view::url('base')?>rss/all"></atom:link>
		<language>en</language>
		<copyright>The Moscow Times, <?php view::text(date('Y')); ?></copyright>
		<lastBuildDate><?php view::text(date('r')) ?></lastBuildDate>
		<ttl>600</ttl>
		<?php foreach($items as $item): ?>
		<?php
			$type = 'Article';
			if ($item->type == 'default') {
				$type = 'Article';
			} elseif ($item->type == 'video') {
				$type = 'Video';
			} elseif ($item->type == 'podcast') {
				$type = 'Podcast';
			} elseif ($item->type == 'gallery') {
				$type = 'Рhotos';
			}

			$slug = 'News';
			if ($item->business) {
				$slug = 'Business';
			} elseif ($item->opinion) {
				$slug = 'Opinion';
			} elseif ($item->city) {
				$slug = 'Arts & Life';
			} elseif ($item->ecology) {
				$slug = 'Climate';
			} elseif ($item->tourism) {
				$slug = 'Tourism';
			} elseif ($item->diaspora) {
				$slug = 'The New Diaspora';
			} elseif ($item->gallery) {
				$slug = 'Photos';
			} elseif ($item->video) {
				$slug = 'Videos';
			} elseif ($item->ukraine_war) {
				$slug = 'Ukraine War';
			} elseif ($item->lecture_series) {
				$slug = 'TMT Lecture Series';
			} elseif ($item->news) {
				$slug = 'News';
			}
		?>
		<item>
			<title>
				<?php
					if ($item->opinion && $item->authors) {
						$authorTags = [];
						$authorHtml = '';
						$separator = '';
						foreach ($item->authors as $author) {
							$authorTags[] = $author->title;
						}
						while($authorTag = array_shift($authorTags)) {
							$authorHtml .= $separator . $authorTag;
							if(count($authorTags) > 1) {
								$separator = ', ';
							} else {
								$separator = ' and ';
							}
						}
						view::raw($authorHtml . ': ');
					}
				?>
				<?php view::text($item->title); ?>
			</title>
			<link><?php view::route('article', $item->data()); ?></link>
			<author>general@themoscowtimes.com</author>
			<description>
				<?php if ($item->type == 'live'): ?>
				<?php foreach ($item->excerpt_live as $excerpt): ?>
				<![CDATA[<?php view::text($excerpt . '.'); ?>]]>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php if (is_array($item->body)): ?>
				<![CDATA[
				<?php foreach($item->body as $index => $block): ?>
					<?php if($block['type'] == 'html' && !empty($block['body'])): ?>
						<?php view::raw($block['body']); ?>
						<?php
							/*
							$body = '';
							$pieces = explode('</p>', $block['body']);
							foreach($pieces as $piece) {
								$stripped = strip_tags(html_entity_decode($piece), '<p> <h1> <h2> <h3> <h4>');	
								$body = str_ireplace(['/p>', '/h1>', '/h2>', '/h3>', '/h4>'], ['/p>', '/h1>', '/h2>', '/h3>', '/h4>'], $stripped);
								$body = strip_tags($body);
								view::text($body);
							}
							*/
						?>
						<?php elseif($block['type'] == 'html' && $index < 1): ?>
						<?php view::text($item->excerpt ? $item->excerpt : ($item->intro ? $item->intro : $item->subtitle)); ?>
					<?php endif; ?>
				<?php endforeach; ?>
				]]>
				<?php endif; ?>
			</description>
			<category>
				<?php view::text($type) ?> - <?php view::text($slug); ?>
			</category>
			<?php if($item->type == 'video'): ?>
			<enclosure url="<?php view::text(trim($item->video)); ?>" length="1" type="text/plain" />
			<?php elseif(!empty($item->image)): ?>
			<enclosure url="<?php view::src($item->id, 'og'); ?>" length="1" type="image/jpeg" />
			<?php endif; ?>
			<pubDate><?php view::text(date('r', strtotime($item->time_publication))) ?></pubDate>
			<guid><?php view::route('article', $item->data()); ?></guid>
		</item>
		<?php endforeach; ?>
	</channel>
</rss>