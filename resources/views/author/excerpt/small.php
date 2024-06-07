<?php $modifier = $modifier ?? ($item->sponsored ? 'sponsored' : $item->section) ;?>
<?php $bem = fetch::bem('author-excerpt-small', $context ?? null, $modifier ?? null) ?>


<div class="<?php view::attr($bem());?>">
	<div class="row-flex ">
		<div class="col-auto col-auto-xs">
			<div>

				<?php if ($item->image): ?>
					<a class="" href="<?php view::route('author', ['slug' => $item->slug]) ?>" title="<?php view::attr($item->title) ?>">
						<img src="<?php view::src($item->image, '320') ?>" />
					</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="col">
			<div class="<?php view::attr($bem('content'));?>">
				<a class="" href="<?php view::route('author', ['slug' => $item->slug]) ?>" title="<?php view::attr($item->title) ?>">
					<h3 class="<?php view::attr($bem('name'));?>"><?php view::text($item->title) ?></h3>
					<span class="<?php view::attr($bem('description'));?>">
						<?php view::text($item->body); ?>
					</span>
				</a>
				<?php if ($item->email!=''): ?>
					<a class="<?php view::attr($bem('email'));?>" href="mailto:<?php view::attr($item->email); ?>" target="_blank" title="<?php view::attr($item->email); ?>"><i class="fa fa-envelope"></i>&nbsp;<?php view::attr($item->email) ?></a>
				<?php endif; ?>
				<?php if ($item->twitter!=''): ?>
					<a class="<?php view::attr($bem('twitter'));?>" href="https://www.twitter.com/<?php view::attr(trim($item->twitter,'@')); ?>" title="twitter.com/<?php view::attr(trim($item->twitter,'@')); ?>" target="_blank"><i class="fa fa-twitter"></i>&nbsp;@<?php view::attr(trim($item->twitter,'@')) ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
