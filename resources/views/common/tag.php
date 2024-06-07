<a
	href="<?php view::route('tag', ['tag' => $item->slug ]) ?>"
	class="tag <?php view::attr( isset($context)?$context.'__tag':''); ?>"
	title="<?php view::attr(ucfirst($item->title)) ?>"
>
	<?php view::text(ucfirst($item->title)) ?>
</a>
