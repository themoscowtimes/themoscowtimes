<figure class="article__image">
	<?php
	
	
	
	if (isset($block['image']['junction']) && isset($block['image']['junction']['url']) && trim($block['image']['junction']['url'])) {
		$url = $block['image']['junction']['url'];
		if(strpos($url, 'http') !== 0 && strpos($url, '//') !== 0) {
			$url = 'https://' . $url;
		}
		$a = ['<a href="' . fetch::attr($url) . '">', '</a>'];
	} else {
		$a = ['', ''];
	}
	?>

	<?php view::raw($a[0]); ?>
	
		<?php if (isset($block['image']['junction']) && ($block['image']['junction']['caption'] || $block['image']['junction']['credits'])): ?>
	
			<img src="<?php view::src($block['image'], '1360') ?>" alt="
				<?php if ($block['image']['junction']['caption']) { view::text($block['image']['junction']['caption']); } ?>
				<?php echo ' '; ?>
				<?php if ($block['image']['junction']['credits']) { view::text($block['image']['junction']['credits']); } ?>
			" />
		<?php else: ?>
			<img src="<?php view::src($block['image'], '1360') ?>" />
		<?php endif; ?>
	<?php view::raw($a[1]); ?>


	<?php if (isset($block['image']['junction']) && ($block['image']['junction']['caption'] || $block['image']['junction']['credits'])): ?>
		<figcaption>
			<?php if ($block['image']['junction']['caption']): ?>
				<span class="article__image__caption"><?php view::text($block['image']['junction']['caption']); ?></span>
			<?php endif; ?>
			<?php if ($block['image']['junction']['credits']): ?>
				<span class="article__image__credits"><?php view::text($block['image']['junction']['credits']); ?></span>
			<?php endif; ?>
		</figcaption>
	<?php endif; ?>
</figure>