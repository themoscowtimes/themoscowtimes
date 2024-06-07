<?php $bem = fetch::bem('event', $context ?? null, $modifier ?? null) ?>

<?php view::extend('template/default'); ?>

<?php view::start('main') ?>
	<?php view::manager('event', $item->id); ?>
	<div class="container">
		<div class="row-flex">
			<div class="col" >
				<article class="event">
					<?php if ($item->image): ?>
						<figure class="event__featured-image">
							<img src="<?php view::src($item->image, 'article_1360') ?>" />
						</figure>
					<?php endif; ?>
					<div class="row-flex">
						<div class="col-auto">
							<div class="event__details">
								
								
								<div class="event__time">
									<?php if($item->show_time): ?>
										<?php view::text(date('d-m-Y H:i', strtotime($item->time))); ?>
									<?php else: ?>
										<?php view::text(date('d-m-Y', strtotime($item->time))); ?>
									<?php endif; ?>


									<?php if($item->show_time_end): ?>
										<?php if($item->show_time): ?>
											<?php view::text(date('d-m-Y H:i', strtotime($item->time_end))); ?>
										<?php else: ?>
											<?php view::text(date('d-m-Y', strtotime($item->time_end))); ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>


								<?php foreach ($item->locations as $location): ?>


									<div class="details-list">
										<div class="details-list__item">
											<span class="details-list__indent"><i class="fa fa-map-marker"></i></span>
											<div class="details-list__descripton">
												<a class="event__location__name" href="<?php view::route('location', ['slug' => $location->slug]) ?>" title="<?php view::attr($location->title); ?>">
													<strong><?php view::text($location->title); ?></strong>
												</a>
											</div>
										</div>
										<?php if ($time = $location->junction('time') ): ?>
											<div class="details-list__item">
												<span class="details-list__indent"><i class="fa fa-clock-o"></i></span>
												<div class="details-list__descripton">
													<?php view::text($time); ?>
												</div>
											</div>
										<?php endif; ?>
											<?php if ($info = $location->junction('info') ): ?>
												<div class="details-list__item">
													<span class="details-list__indent"><i class="fa fa-info"></i></span>
													<div class="details-list__descripton">
														<?php view::text($info); ?>
													</div>
												</div>
											<?php endif; ?>

										</div>


									<?php endforeach; ?>
								

								<?php view::file('common/social', ['item'=>$item]) ?>
							</div>

						</div>
						<div class="col">
							<div class="">
								<header class="item-header event__header">
									<?php view::file('common/label', ['item'=>$item, 'context' => $bem->block()]) ?>
									<h1><?php view::text($item->title) ?></h1>
									<h2><?php view::text($item->subtitle) ?></h2>
								</header>
								<div class="event__intro">
									<?php view::raw(nl2br(strip_tags($item->intro))); ?>
								</div>
								<div class="event__content">
									<?php view::raw($item->body) ?>
								</div>
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
					<?php if (isset($events)): ?>
						<section class="sidebar__section">
							<div class="sidebar__section__header">
								<h3 class="sidebar__section__label header--style-3 "><?php view::lang('Upcoming events'); ?></h3>

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

<?php view::end(); ?>


