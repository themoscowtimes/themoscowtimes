<?php $bem = fetch::bem('event-excerpt-horizontal', $context ?? null, $modifier ?? null) ?>

<div class="excerpt-horizontal <?php view::attr($bem()) ?>">
	<a href="<?php view::route('event', ['slug' => $item->slug]) ?>" title="<?php view::attr($item->title) ?>">
	<div class="row-flex">
		<div class="col-auto">
			<figure class="imagewrap-square-120 <?php view::attr($bem('image')); ?>">
				<?php if ($item->image): ?>
					<img class="" src="<?php view::src($item->image, '320') ?>" />
				<?php endif; ?>
			</figure>
		</div>
		<div class="col">
			<div class="<?php view::attr($bem('content')) ?>">
				<div class="label label--type-2 label--color-8">
					<?php if($item->show_time): ?>
						<?php view::text(date('d-m-Y H:i', strtotime($item->time))); ?>
					<?php else: ?>
						<?php view::text(date('d-m-Y', strtotime($item->time))); ?>
					<?php endif; ?>
				</div>
				<h3 class="header--style-4 <?php view::attr($bem('headline')) ?>">
					<?php view::text($item->title) ?>
					<span class=" label label--color-4 <?php view::attr($bem('headline')) ?>__label"><?php view::raw($item->type) ?></span>
				</h3>
				<?php view::raw($item->body) ?>


				<?php if ($item->location): ?>
					<?php view::raw($item->location->title) ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	</a>
</div>