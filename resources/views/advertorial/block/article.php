<?php if (isset($block['article']) && isset($block['article']['id'])): ?>
	<?php

	$modifier = 'default';

	$label = fetch::lang('news');

	if(isset($block['article']['sponsored']) && $block['article']['sponsored'] == '1') {
		$label = fetch::lang('sponsored');
		$modifier = 'sponsored';
	} elseif(isset($block['article']['opinion']) && $block['article']['opinion'] == '1') {
		$label = fetch::lang('opinion');
		$modifier = 'opinion';
	} elseif(isset($block['article']['meanwhile']) && $block['article']['meanwhile'] == '1') {
		if (isset($context) && $context != 'article') {
			$modifier = 'meanwhile';
		}
	}
	?>
	<aside class="article__related-article">
		<span class="label related-article__label label--<?php view::attr($modifier);?>"><?php view::text($label); ?></span>
		<a class="related-article__inner" href="<?php view::route('article', ['slug' => $block['article']['slug']]) ?>" title="<?php view::attr($block['article']['title']) ?>">
			<h3 class="related-article__title">
				<?php view::text($block['article']['title']) ?>
			</h3>
			<span class="related-article__cta">Read more</span>
		</a>
	</aside>
<?php endif; ?>



