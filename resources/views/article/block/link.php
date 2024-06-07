<?php if(isset($block['link']) && isset($block['link']['url'])): ?>
	<div class="article__link">
		<a href="<?php view::attr($block['link']['url']) ?>" <?php echo isset($block['link']['blank']) && $block['link']['blank'] == '1' ? 'target="_blank"' : ''; ?>  <?php echo isset($block['link']['nofollow']) && $block['link']['nofollow']=='1' ? 'rel="nofollow"' : ''; ?> title="<?php view::attr($block['link']['title']); ?>">
			<?php if (isset($block['description']) && $block['description']): ?>
				<div class="article__link__description">
					<?php view::text($block['description']); ?>
				</div>
			<?php endif; ?>
			<?php if (isset($block['link']['title']) && $block['link']['title']): ?>
				<h3 class="article__link__title"><?php view::text($block['link']['title']); ?></h3>
			<?php endif; ?>
		</a>
	</div>
<?php endif; ?>