<ul class="listed-articles">
	<?php foreach($items as $item): ?>
		<li class="listed-articles__item">
			<?php view::file('article/excerpt/tiny', ['item' => $item, 'context'=>'']);?>
		</li>
	<?php endforeach; ?>
</ul>