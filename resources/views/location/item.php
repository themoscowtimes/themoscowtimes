<?php $bem = fetch::bem('location', $context ?? null, $modifier ?? null) ?>

<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'location-item') ?>

<?php view::start('main') ?>
	<?php view::manager('location', $item->id); ?>
	<div class="container">
		<div class="row-flex">
			<div class="col" >
				<article class="location">
					<?php if ($item->image): ?>
						<figure class="location__featured-image">
							<img src="<?php view::src($item->image, 'article_1360') ?>" />
						</figure>
					<?php endif; ?>
					<div class="row-flex">
						<div class="col-auto">
							<div class="location__details" >
								
								<div class="details-section">
									<div class="details-list">
										<?php if($item->open!=''): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-clock-o"></i></span>
												<span class="details-list__description">
													<?php view::text($item->open); ?>
												</span>
											</div>
										<?php endif; ?>
										<?php if($item->metro!=''): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-train"></i></span>
												<span class="details-list__description">
													<?php view::text($item->metro); ?>
												</span>
											</div>
										<?php endif; ?>
										<?php if($item->address!=''): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-map-marker"></i></span>
												<span class="details-list__description">
													<?php view::text($item->address); ?>
												</span>
											</div>
										<?php endif; ?>
										<?php if($item->phone!=''): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-phone"></i></span>
												<span class="details-list__description">
													<?php view::text($item->phone); ?>
												</span>
											</div>
										<?php endif; ?>
										<?php if($item->website!=''): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-globe"></i></span>
												<span class="details-list__description">
													<a href="<?php view::attr($item->website); ?>" target="_blank" title="<?php view::attr($item->website); ?>"><?php view::text($item->website); ?></a>
												</span>
											</div>
										<?php endif; ?>
										<?php if($item->twitter!=''): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-twitter"></i></span>
												<span class="details-list__description">
													<a class="" href="https://www.twitter.com/<?php view::attr(trim($item->twitter,'@')); ?>" target="_blank" title="twitter.com/<?php view::attr(trim($item->twitter,'@')); ?>">@<?php view::attr(trim($item->twitter,'@')) ?></a>
												</span>
											</div>
										<?php endif; ?>
										<?php if($item->facebook!=''): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-facebook"></i></span>
												<span class="details-list__description">
													<a href="http://www.facebook.com/<?php view::attr($item->facebook); ?>" target="_blank" title="facebook.com/<?php view::attr($item->facebook); ?>"><?php view::text($item->facebook); ?></a>
												</span>
											</div>
										<?php endif; ?>
									
									</div>
								</div>
								<?php view::file('common/social', ['item'=>$item]) ?>
							</div>
						</div>
						<div class="col">
							<div class="">
								<header class="item-header location__header">
									<?php view::file('common/label', ['item'=>$item, 'context' => $bem->block()]) ?>
									<h1><?php view::text($item->title) ?></h1>
									<h2><?php view::text($item->subtitle) ?></h2>
								</header>
									<?php view::raw(nl2br(strip_tags($item->description))); ?>
								
							</div>
						</div>
					</div>
				</article>
				
				
				
			</div>
			<div class="col-auto hidden-sm-down">
				<aside class="sidebar" style="">
					<section class="sidebar__section">
						<div class="banner" style="width: 336px; height: 500px; border: 1px solid black;">
							AD
						</div>
					</section>
					
					<?php if ($events): ?>
						<section class="sidebar__section">
							<div class="sidebar__section__header">
								<h3 class="sidebar__section__label header--style-3 "><?php view::lang('Events at this location'); ?></h3>
							</div>
							<?php foreach ($events as $event): ?>
								<div class="mb-2">
									<?php view::file('event/excerpt/tiny', ['item' => $event]); ?>
								</div>
							<?php endforeach; ?>
						</section>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="mb-4">
			banner
		</div>
	</div>
	<div class="container">
		<section class="cluster cluster--section-highlights ">
			<div class="cluster__header">
				<h2 class="cluster__label header--style-3"><?php view::lang('Arts & Lifestyle'); ?></h2>
			</div>
			<div class="row-flex">
				<?php foreach ($city as $article): ?>
					<div class="col-3 col-6-sm">
						<?php view::file('article/excerpt/default', ['item' => $article]); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	</div>



<?php view::end(); ?>


<?php view::start('aside') ?>

<?php view::end(); ?>