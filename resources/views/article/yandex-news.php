<?xml version="1.0" encoding="windows-1251"?>
<rss version="2.0" xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru">
	<channel>
		<title>The Moscow Times - Independent News From Russia</title>
		<link><?php view::url('base'); ?></link>
		<description>The Moscow Times offers everything you need to know about Russia: Breaking news, top stories, business, analysis, opinion, multimedia</description>
		<image>
			<url>https://static.themoscowtimes.com/img/logo_tmt_30_yo.svg</url>
			<title>The Moscow Times - Independent News From Russia</title>
			<link><?php view::url('base'); ?></link>
		</image>

		<?php foreach($items as $item): ?>

    <?php
			$section = 'News';
			if ($item->news) {
				$section = 'News';
			} elseif ($item->business) {
				$section = 'Business';
			} elseif ($item->opinion) {
				$section = 'Opinion';
			} elseif ($item->meanwhile) {
				$section = 'Meanwhile';
			} elseif ($item->diaspora) {
				$section = 'The New Diaspora';
			} elseif ($item->indepth) {
				$section = 'In-Depth';
			} elseif ($item->society) {
				$section = 'Arts & Life';
			} elseif ($item->lecture_series) {
				$section = 'TMT Lecture Series';
			} elseif ($item->ukraine_war) {
				$section = 'Ukraine War';
			}
		?>

		<item>
			<title><?php view::text($item->title); ?></title>
			<link><?php view::route('article', $item->data()); ?></link>
			<description>
			<?php if ($item->excerpt || $item->intro): ?>
			  <?php view::text($item->excerpt ? $item->excerpt : ($item->intro ? $item->intro : $item->subtitle)); ?>
			<?php endif; ?>
			</description>
			<author>
			<?php if ($item->authors): ?>
				<?php $authors = array(); ?>
				<?php foreach ($item->authors as $author): ?>
					<?php array_push($authors, $author['title']); ?>
				<?php endforeach; ?>
				<?php echo implode(', ', $authors); ?>
			<?php endif; ?>
			</author>
			<category>
			<?php echo $section; ?>
			</category>
			<enclosure url="<?php view::src($item->image, 'article_640'); ?>" type="image/jpeg" />
			<pubDate><?php view::text(date('r', strtotime($item->time_publication))); ?></pubDate>
			<yandex:full-text>
			<?php
				$body = '';
				if(is_array($item->body)) {
					foreach($item->body as $block) {
						if($block['type'] == 'html') {
							$stripped = strip_tags(html_entity_decode($block['body']), '<p> <h1> <h2> <h3> <h4>');
							$body = str_ireplace(['/p>', '/h1>', '/h2>', '/h3>', '/h4>'], ['/p>', '/h1>', '/h2>', '/h3>', '/h4>'], $stripped);
							$body = strip_tags($body);
						}
					}
				}
				view::text($body);
			?>
			<?php if ($item->opinion): ?>
				<?php view::lang('disclaimer') ?>
			<?php endif; ?>
			</yandex:full-text>
		</item>
		<?php endforeach; ?>
	</channel>
</rss>