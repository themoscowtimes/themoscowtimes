
<?php $modifier = 'event' ?>
<?php $bem = fetch::bem('event-excerpt-tiny', $context ?? null, $modifier ?? null) ?>

<div class="<?php view::attr($bem()) ?>">
	<a href="<?php view::route('event', ['slug' => $item->slug]); ?>" title="<?php view::attr($item->title) ?>">
		<div class="row-flex gutter-1">
			<?php if ($item->image): ?>
				<div class="col-auto">
					<figure class="event-excerpt-tiny__visual imagewrap-square-65">
						<img src="<?php view::src($item->image, '320') ?>" />
					</figure>
				</div>
			<?php endif; ?>
			<div class="col">
				<h5 class="<?php view::attr($bem('headline')) ?>">
					<time class="<?php view::attr($bem('time')) ?>  timeago"  datetime="<?php view::text($item->time); ?>" y-use="Timeago" >
						<?php view::text($item->time); ?>
						<?php echo strftime('%a %d %b %Y, %R',strtotime($item->time)); ?>

					</time>
					<?php view::text($item->title) ?>
					<?php view::file('common/label', ['item'=>$item, 'context' => $bem->block()]) ?>
				</h5>
			</div>
		</div>
	</a>
</div>
