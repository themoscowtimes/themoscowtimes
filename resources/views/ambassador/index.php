<?php $bem = fetch::bem('page', $context ?? null, $modifier ?? null) ?>

<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('ambassadors')); ?>

<?php view::block('body.class', 'ambassadors page-item'); ?>

<?php view::start('main') ?>

	<div class="container">
		<div class="row-flex gutter-2">
			<div class="col" >
				<article class="page">

					<header class="page__header ">
            <h1>Testimonials</h1>
					</header>

					<div class="page__content-container">
						<div class="page__content">
							<ul class="ambassadors__list">
							<?php if ($items): ?>
							<?php foreach ($items as $item): ?>
								<li>
									<a class="ambassadors__link" href="<?php view::url('base'); ?>ambassador/<?php view::text($item->slug); ?>">
										<div class="ambassadors__card">
											<?php if ($item->image): ?>
												<figure class="image image--featured">
													<img data-src="<?php view::src($item->image) ?>" width="90" height="90" alt="<?php view::text($item->title) ?>"  class="lazyload">
												</figure>
											<?php endif; ?>
											<div class="ambassadors__card__info">
												<h2><?php view::text($item->title); ?></h2>
												<p><?php view::text($item->subtitle); ?></p>
											</div>
										</div>
										<div class="ambassadors__info">
											<p><?php view::text($item->intro); ?></p>
											<p class="ambassadors__read-more">
												<span class="ambassadors__read-more__link">Читать полностью</span>
											</p>
										</div>
									</a>
								</li>
							<?php endforeach; ?>
							<?php endif; ?>
							</ul>
						</div>
					</div>
				</article>
			</div>


			<div class="col-auto hidden-sm-down">
				<aside class="sidebar" style="">
					<section class="sidebar__section">
						<?php $banner = fetch::banner('other_1') ?>
					</section>
					<div class="sidebar__sticky">
						<section class="sidebar__section">
							<div class="sidebar__section__header">
								<p class="header--style-3"><?php view::lang('Most read') ?></p>
							</div>
							<?php view::mostread(); ?>
						</section>
					</div>
				</aside>
			</div>
		</div>
	</div>
<?php view::end(); ?>