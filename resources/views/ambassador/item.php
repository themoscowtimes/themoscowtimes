<?php $bem = fetch::bem('page', $context ?? null, $modifier ?? null) ?>

<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('page', ['item' => $item])) ?>


<?php view::block('body.class', 'ambassador') ?>


<?php view::start('main') ?>
	<?php view::manager('page', $item->id); ?>

	<div class="container">
		<div class="row-flex gutter-2">
			<div class="col" >
				<article class="page">

					<header class="page__header ">
						<?php if ($item->image): ?>
							<figure class="image image--featured" >
								<img src="<?php view::src($item->image) ?>" width="90" height="90" alt="<?php view::text($item->title) ?>">
							</figure>
						<?php endif; ?>
						<h1><?php view::text($item->title) ?></h1>
						<h2><?php view::text($item->subtitle) ?></h2>
					</header>

					<?php /* if ($item->intro != ''): ?>
						<div class="page__intro">
							<?php view::raw(nl2br(strip_tags($item->intro))); ?>
						</div>
					<?php endif; */ ?>

					<div class="page__content-container">
						<div class="page__content">
							<?php view::raw($item->body) ?>
							<p></p>
							<p><a class="button button--color-3" href="<?php view::url('base'); ?>contribute">Contribute today</a></p>
						</div>
						<?php view::file('common/social', ['item'=>$item]) ?>
					</div>
				</article>
			</div>


			<div class="col-auto hidden-sm-down">
				<aside class="sidebar" style="">
					<!-- vaste breedte 336 -->
					<?php view::start('sidebar__section') ?>
							<?php view::banner('other_1') ?>
						<?php view::end(); ?>
					<div class="sidebar__sticky">
						<section class="sidebar__section">
							<?php view::banner('section_1') ?>
						</section>
					</div>
				</aside>
			</div>
		</div>
	</div>
<?php view::end(); ?>