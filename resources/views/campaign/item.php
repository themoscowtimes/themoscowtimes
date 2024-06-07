<?php $bem = fetch::bem('campaign', $context ?? null, $modifier ?? null) ?>

<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('campaign', ['item' => $item])) ?>

<?php view::block('body.class', 'campaign-item') ?>

<?php view::start('main') ?>
	<?php view::manager('campaign', $item->id); ?>

	<div class="container">
		<div class="row-flex gutter-2">
			<div class="col-auto ">
				<aside class="sidebar">
					<section class="sidebar__section campaign__header">
						<div class="campaign__plus plus">
							MT<sup class="plus__sub">+</sup>
						</div>
						<?php if ($item->logo): ?>
							<figure class="campaign__logo" >
								<img class="campaign__logo__img" src="<?php view::src($item->logo, '320') ?>" title="<?php view::text($item->title ? $item->title :'') ?>" />
							</figure>
						<?php endif; ?>

						<?php if ($item->body != ''): ?>
							<div class="campaign__intro">
								<?php view::raw(nl2br(strip_tags($item->body))); ?>
							</div>
						<?php endif; ?>

						<?php if ($item->url != ''): ?>
							<a class="campaign__button button button--style-2" href="<?php view::text($item->url) ?>"/>More information</a>
						<?php endif; ?>

						<div class="campaign__disclaimer">
							<strong>MT<sup>+</sup> is branded content of The Moscow Times.</strong><br/>This content is provided by outside parties.
						</div>

					</section>
				</aside>
			</div>
			<div class="col" >
				<article class="campaign">





					<?php if ($item->image): ?>
						<figure class="campaign__featured-image featured-image" >
							<img src="<?php view::src($item->image, 'article_1360') ?>" />
							<?php if ($item->caption!='' || $item->credits!=''): ?>
								<figcaption class="">
									<span class="campaign__featured-image__caption featured-image__caption">
										<?php view::text($item->caption) ?>
									</span>
									<span class="campaign__featured-image__credits featured-image__credits">
										<?php view::text($item->credits) ?>
										</span>
								</figcaption>
							<?php endif; ?>
						</figure>
					<?php endif; ?>

					<div class="row-flex">
						<?php foreach ($advertorials as $index => $advertorial): ?>
							<?php
							if($index === 0){
								$view = 'advertorial/excerpt/lead';
								$primary = true;
								$col = 'col-12 col-12-md col-12-sm';
								$image = null;
							} else {
								$view = 'advertorial/excerpt/default';
								$primary = false;
								if($index - 4 >= 0 && ($index - 4) % 5 === 0) {
									$image = 'article_960';
									$col = 'col-8 col-12-md col-4-sm';
								} else {
									$image = 'article_640';
									$col = 'col-4 col-6-md col-4-sm';
								}
							}
							?>
							<div class="<?php view::attr($col) ?>">
								<?php view::file($view, [
									'item' => $advertorial,
									'modifier' => $primary ? 'primary' : null,
									'image' => $image,
									'campaign' => $item
								]); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</article>
			</div>
		</div>
	</div>
<?php view::end(); ?>