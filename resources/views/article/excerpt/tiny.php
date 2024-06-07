<?php $bem = fetch::bem('article-excerpt-tiny', $context ?? null, $modifier ?? null) ?>

<div class="<?php view::attr($bem()) ?>">
	<a 
		href="<?php view::route('article', $item->data()); ?>"
		title="<?php view::text($item['title']) ?>"
		data-track="just-in-link <?php view::text($item['title']) ?>"
	>
		<?php if(! isset($time) || $time): ?>
		<time class="<?php view::attr($bem('time')) ?>  "
			datetime="<?php view::text(date('c', strtotime($item->time_publication))); ?>" y-use="Timeago">
			<?php view::date(strtotime($item->time_publication)); ?>
		</time>
		<?php endif; ?>
		<h5 class="<?php view::attr($bem('headline')) ?> ">
			<?php view::text($item['title']) ?>
		</h5>
	</a>
</div>