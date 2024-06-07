<?php $bem = fetch::bem('issue-excerpt-extended', $context ?? null, $modifier ?? null) ?>


<?php $show_nav = (isset($issue_nav) && $issue_nav == true) ? true : false; ?>

<div class="<?php view::attr($bem()); ?>">
	<?php if ($show_nav): ?>
		<a href="<?php view::route('issue', ['number' => $item->number]) ?>" class="<?php view::attr($bem('link')); ?>" title="<?php view::attr($item->title) ?>">
	<?php endif; ?>
		<div class="<?php view::attr($bem('padding')); ?>">
			<div class="<?php view::attr($bem('number')); ?>">
				<?php view::text($item->number) ?>
			</div>
			<span class="<?php view::attr($bem('title')); ?>">
				<?php view::date($item->date); ?>
			</span>

			<?php if ($item->image): ?>
				<div class="<?php view::attr($bem('cover')); ?>">
					<figure>
						<img src="<?php view::src($item->image, '640') ?>" />
					</figure>
				</div>
			<?php endif; ?>

			<?php if (isset($intro) && $intro == true): ?>
				<div class="<?php view::attr($bem('intro')); ?>">
					<?php view::text($item->intro) ?>
				</div>
			<?php endif; ?>
		</div>
	<?php if ($show_nav): ?>
		</a>
	<?php endif; ?>

	<div class="<?php view::attr($bem('actions')); ?>">
		<?php if($item->file): ?>
			<a href="<?php view::attr(fetch::url('static') . 'files/' . $item->file->path . $item->file->file) ?>" target="_blank" class="<?php view::attr($bem('action')); ?>" title="<?php view::lang('Download'); ?>">
				<?php view::lang('Download'); ?>
			</a>
		<?php endif; ?>
		<?php if ($show_nav): ?>
			<a href="<?php view::route('issue', ['number' => $item->number]) ?>" class="<?php view::attr($bem('action')); ?>" title="<?php view::lang('See issue'); ?>">
				<?php view::lang('See issue'); ?>
			</a>
		<?php endif; ?>
	</div>
</div>