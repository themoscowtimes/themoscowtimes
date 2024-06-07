<?php $bem = fetch::bem('article-excerpt-ranked', $context ?? null, $modifier ?? null) ?>

<div class="<?php view::attr($bem()) ?>">
	<a href="<?php view::route('article', $item->data()); ?>" title="<?php view::attr($item->title); ?>">
		<div class="<?php view::attr($bem('rank')) ?>">
			<?php view::text($rank); ?>
		</div>
		<div class="<?php view::attr($bem('item')) ?>">
			<?php if(! isset($label) || $label): ?>
				<?php view::file('common/label', ['item' => $item, 'context' => $bem->block()]) ?>
			<?php endif; ?>
			<?php if($item->opinion && is_array($item->authors) && count($item->authors) > 0 ) : ?>
				<span class="<?php view::attr($bem('author')) ?>">
					<?php view::text($item->authors[0]->title); ?>
				</span>
			<?php endif; ?>
			<h5 class="<?php view::attr($bem('headline')) ?>">
				<?php view::text($item->title); ?>
			</h5>
		</div>
	</a>
</div>