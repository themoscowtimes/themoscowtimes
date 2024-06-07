<?php $bem = fetch::bem('location-excerpt-horizontal', $context ?? null, $modifier ?? null) ?>

<div class="excerpt-horizontal <?php view::attr($bem()) ?>">
	<a href="<?php view::route('location', ['slug' => $item->slug]) ?>" title="<?php view::attr($item->title) ?>">
	<div class="row-flex">
		<?php if ($item->image): ?>
			<div class="col-auto">
				<figure class="imagewrap-square-120 <?php $bem('image'); ?>">
					<img src="<?php view::src($item->image, '320') ?>" />
				</figure>
			</div>
		<?php endif; ?>
		
		<div class="col">
			<div class="<?php view::attr($bem('content')) ?>">
				
				<h3 class="header--style-4 <?php view::attr($bem('headline')) ?>">
					<?php view::text($item->title) ?>
					<span class=" label label--color-10 <?php view::attr($bem('headline')) ?>__label"><?php view::raw($item->type) ?></span>
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