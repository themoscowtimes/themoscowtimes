<div class="campaign campaign--excerpt mt-3">
	<?php if ($item->body != ''): ?>
		<div class="campaign__intro">
			<?php view::raw(nl2br(strip_tags($item->body))); ?>
		</div>
	<?php endif; ?>

	<?php if ($item->logo): ?>
		<figure class="campaign__logo" >
			<img class="campaign__logo__img" src="<?php view::src($item->logo, '320') ?>" title="<?php view::text($item->title ? $item->title :'') ?>" />
		</figure>
	<?php endif; ?>

	<?php if ($item->url != ''): ?>
		<a class="campaign__button button button--style-2" href="<?php view::text($item->url) ?>">More information</a>
	<?php endif; ?>
</div>