<?php $bem = fetch::bem('author-excerpt-default', $context ?? null, $modifier ?? null) ?>

<div class="<?php view::attr($bem());?>">
	<a class="" href="<?php view::route('author', ['slug' => $item->slug]) ?>" title="<?php view::attr($item->title) ?>">
		<h3 class="<?php view::attr($bem('name'));?>"><?php view::text($item->title) ?></h3>
		<?php if ($item->image): ?>
			<?php if (false && isset($_GET["amp"]) == 1): ?>
				<!-- no author image for AMP -->
 			<?php else: ?>
				<img src="<?php view::src($item->image, '320') ?>" />
			<?php endif; ?>
		<?php endif; ?>
		<span class="<?php view::attr($bem('description'));?>">
			<?php view::text($item->body); ?>
		</span>
	</a>
	<?php if (false && isset($_GET["amp"]) == 1): ?>
		<!-- no twitter or email for AMP -->
		<?php else: ?>
	<?php if ($item->email!=''): ?>
		<a class="<?php view::attr($bem('email'));?>" href="mailto:<?php view::attr($item->email); ?>" target="_blank" title="<?php view::attr($item->email); ?>"><i class="fa fa-envelope"></i>&nbsp;<?php view::attr($item->email) ?></a>
	<?php endif; ?>
	<?php if ($item->twitter!=''): ?>
		<a class="<?php view::attr($bem('twitter'));?>" href="https://www.twitter.com/<?php view::attr(trim($item->twitter,'@')); ?>" target="_blank" title="twitter.com/<?php view::attr(trim($item->twitter,'@')); ?>"><i class="fa fa-twitter"></i>&nbsp;@<?php view::attr(trim($item->twitter,'@')) ?></a>
	<?php endif; ?>
	<?php endif; ?>
</div>