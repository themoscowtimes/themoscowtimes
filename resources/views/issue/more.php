<?php foreach ($items as $item): ?>
	<div class="col-4 col-6-xs mb-3">
		<?php view::file('issue/excerpt/extended', ['item' => $item, 'issue_nav'=>true]); ?>
	</div>
<?php endforeach; ?>
