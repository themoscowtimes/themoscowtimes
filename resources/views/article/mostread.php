<ul class="ranked-articles">
	<?php $rank = 0; ?>
	<?php foreach($items as $item): ?>
		<?php $rank++; ?>
		<li class="ranked-articles__item">
			<?php view::file('article/excerpt/ranked', ['item' => $item, 'context'=>'ranked-articles', 'rank' => $rank]);?>
		</li>
	<?php endforeach; ?>
</ul>