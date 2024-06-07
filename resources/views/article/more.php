<?php foreach ($items as $item): ?>
	<div class="col-4 col-12-md col-4-sm">
		<?php view::file('article/excerpt/default', [
			'item' => $item,
			'label' =>  $section == 'opinion' ||  $section == 'indepth'  ? '' : true,
		]); ?>
	</div>
<?php endforeach; ?>
