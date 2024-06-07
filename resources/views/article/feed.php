<root>
	<?php foreach($items as $item): ?>

	<entry>
		<url><?php view::route('article', $item->data()); ?></url>
		<title><?php view::text($item->title); ?></title>
		<published><?php view::text(date('Y-m-d H:i', strtotime($item->time_publication))); ?></published>
		<category><?php view::lang(fetch::section($item)) ?></category>
		<text><?php
		$body = '';
		if($item->title) {
			$body .= '<h1>' . fetch::text($item->title) . '</h1>';
		}
		if($item->subtitle) {
			$body .= '<h2>' . fetch::text($item->subtitle) . '</h2>';
		}
		if($item->intro) {
			$body .= '<p>' . nl2br(strip_tags($item->intro)) . '</p>';
		}

		if(is_array($item->body)) {
			foreach($item->body as $block) {
				if($block['type'] == 'header') {
					$body .= '<h3>' . fetch::text($block['title']) . '</h3>';
				}
				if($block['type'] == 'html') {
					$body .= $block['body'];
				}
			}
		}
		view::text(html_entity_decode($body));
		?></text>
		<authors><?php foreach ($item->authors as $author): ?>
			<author><?php view::text($author->title) ?></author>
		<?php endforeach; ?></authors>
	</entry>
	<?php endforeach; ?>
</root>