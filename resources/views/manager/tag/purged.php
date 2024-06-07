<?php view::extend('template/main'); ?>


<?php view::start('main') ?>
	<div class="content-header" y-name="header-fixed">
		<div class="content-header-1">
			<h2>Removed unused tags</h2>
		</div>
	</div>

	<div class="row m-2">
		<div class="col">
			<?php foreach($tags as $id => $title): ?>
				<?php view::text($title) ?><br />
			<?php endforeach; ?>
		</div>
	</div>

<?php view::end() ?>