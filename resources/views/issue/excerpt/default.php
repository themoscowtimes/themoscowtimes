<?php $bem = fetch::bem('issue-excerpt-default', $context ?? null, $modifier ?? null) ?>

<div class="<?php view::attr($bem()); ?>">
	<a href="<?php view::route('issue', ['number' => $item->number]) ?>" title="<?php view::attr($item->title); ?>">
		<?php if ($item->image): ?>
			<div class="<?php view::attr($bem('cover')); ?>">
				<figure>
					<img src="<?php view::src($item->image, '640') ?>" />
				</figure>
			</div>
		<?php endif; ?>
		<div class="<?php view::attr($bem('title')); ?>">
			<?php view::date(strtotime($item->date)); ?>
		</div>
	</a>
</div>