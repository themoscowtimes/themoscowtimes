<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'partner') ?>

<?php view::start('main') ?>

<div class="container">
	<div class="cluster__header mb-3">
		<h2 class="cluster__label header--style-3">Partner Articles by <?php view::text($item->title) ?></h2>
	</div>
</div>
<div class="container">
	<div class="row-flex">
		<div class="col">
			<section class="cluster cluster--section-articles mb-4">
				<div class="row-flex">
					<?php foreach ($articles as $article): ?>
						<div class="col-4 col-6-sm">
							<?php view::file('article/excerpt/default', ['item' => $article]); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		</div>

		<div class="col-auto">
			<aside class="sidebar" style="">
				<section class="sidebar__section">
					<?php view::file('author/excerpt/default', ['item' => $item]); ?>
				</section>
				<section class="sidebar__section">
					<?php view::banner('section_1') ?>
				</section>
			</aside>
		</div>
	</div>
</div>

<?php view::end(); ?>