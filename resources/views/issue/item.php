<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'issue-item') ?>

<?php view::start('main') ?>
	<?php view::manager('issue', $item->id); ?>
	<div class="container">
		<h3 class="header--style-3 mb-2 "><?php view::text($item->title) ?></h3>
	</div>
	<div class="container">

		<div class="row-flex">
			<div class="col" >
				<section class="cluster cluster--section-articles ">

					<div class="row-flex">
						<?php $index = 0; ?>
						<?php foreach ($item->articles as $article): ?>
							<?php if($article->status == 'live'): ?>
								<?php $modifier = $index==0?'':''; ?>

								<div class="col-4 col-6-md col-6-sm col-12-xs" >
									<?php view::file('article/excerpt/default', ['item' => $article, 'context'=>'issue']); ?>
								</div>
								<?php $index++; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</section>

			</div>
			<div class="col-auto">
				<aside class="sidebar" style="">
					<section class="sidebar__section">
						<?php view::file('issue/excerpt/extended', ['item' => $item, 'intro'=>true]); ?>
						<a href="<?php view::route('issues') ?>" class="button button--full-width" alt="<?php view::lang('See all issues'); ?>"><?php view::lang('See all issues'); ?></a>
					</section>
				</aside>
			</div>

		</div>

	</div>


<?php view::end(); ?>


<?php view::start('aside') ?>

<?php view::end(); ?>
