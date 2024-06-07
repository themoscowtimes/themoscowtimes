<?php foreach ($articles as $article): ?>
	<div class="col-4 col-12-md col-4-sm">
		<?php view::file('article/excerpt/default', ['item' => $article]); ?>
	</div>
<?php endforeach; ?>
