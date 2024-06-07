<a href="<?php view::route('article', $item->data()); ?>" class="live-excerpt">
	<?php if ($item->image): ?>
		<picture class="live-excerpt__picture">
			<source media="(min-width: 960px)" srcset="<?php view::src($item->image, '1360') ?>">
			<source media="(min-width: 640px)" srcset="<?php view::src($item->image, '960') ?>">
			<img class="live-excerpt__img" y-name="img" src="<?php view::src($item->image, '960') ?>" alt=""  />
		</picture>
	<?php endif; ?>
	<div class="live-excerpt__content">
		<div class="live-excerpt__label label label--live">live</div>
		<h2 class="live-excerpt__headline"><?php view::text($item->title) ?></h2>
		<div class="live-excerpt__posts row-flex">
			<?php if (is_array($item->excerpt_live)): ?>
				<?php foreach ($item->excerpt_live as $index => $bullet): ?>
					<?php if ($index < 3): ?>
						<div class="live-excerpt__post col-4 col-4-md col-4-sm">
							<?php view::text($bullet); ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	</div>
</a>