<?php $bem = fetch::bem('page', $context ?? null, $modifier ?? null) ?>

<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('dossier', ['item' => $item])) ?>

<?php view::block('body.class', 'dossier-item') ?>


<?php view::start('main') ?>
	<?php view::manager('dossier', $item->id); ?>

	<div class="container">
		<div class="row-flex gutter-2">
			<div class="col" >
				<article class="dossier">

					<header class="dossier__header ">
						<h1><?php view::text($item->title) ?></h1>
						<h2><?php view::text($item->subtitle) ?></h2>
					</header>

					<?php if ($item->intro != ''): ?>
						<div class="dossier__intro">
							<?php view::raw(nl2br(strip_tags($item->intro))); ?>
						</div>
					<?php endif; ?>

					<?php /* if ($item->image): ?>
						<figure class="dossier__featured-image featured-image" >
							<img src="<?php view::src($item->image, 'article_1360') ?>" />
							<?php if ($item->caption!='' || $item->credits!=''): ?>
								<figcaption class="">
									<span class="dossier__featured-image__caption featured-image__caption">
										<?php view::text($item->caption) ?>
									</span>
									<span class="dossier__featured-image__credits featured-image__credits">
										<?php view::text($item->credits) ?>
										</span>
								</figcaption>
							<?php endif; ?>
						</figure>
					<?php endif; */?>

					<?php /*
					<div class="dossier__content-container">
						<div class="dossier__content">
							<?php view::raw($item->body) ?>
						</div>
						<?php view::file('common/social', ['item'=>$item]) ?>
					</div>
					 */?>


					<div class="row-flex">
						<?php $index = 0 ?>
						<?php foreach ($articles as $article): ?>
							<?php
							if($index === 0){
								$view = 'article/excerpt/lead';
								$primary = true;
								$col = 'col-12 col-12-md col-12-sm';
								$image = null;
							} else {
								$view = 'article/excerpt/default';
								$primary = false;
								if($index - 4 >= 0 && ($index - 4) % 5 === 0) {
									$image = 'article_960';
									$col = 'col-8 col-12-md col-4-sm';
								} else {
									$image = 'article_640';
									$col = 'col-4 col-6-md col-4-sm';
								}
							}
							$index++;
							?>
							<div class="<?php view::attr($col) ?>">
								<?php view::file($view, ['item' => $article, 'modifier' => $primary ? 'primary' : null, 'image' => $image]); ?>
							</div>
						<?php endforeach; ?>

						<div class="col-12" y-use="More" data-url="<?php view::route('dossier', ['slug' => $item->slug, 'offset' => '{{offset}}']) ?>" data-start="19" data-step="18">
							<div class="align-center">
								<span title="View more articles" class="button">View more articles</span>
							</div>
						</div>
					</div>
				</article>


			</div>


			<div class="col-auto hidden-sm-down">
				<aside class="sidebar">
					<section class="sidebar__section">
						<?php $banner = fetch::banner('other_1') ?>
					</section>
					<div class="sidebar__sticky">
						<section class="sidebar__section">
							<div class="tabs" y-use="Tabs" data-active="tabs__tab--active">
								<section class="sidebar__section">
									<div class="sidebar__section__header">
										<div class="tabs__tab" y-name="tab" data-content="mostread">
											<h3 class="tab__header header--style-3">Most read</h3>
										</div>
										<div class="tabs__tab" y-name="tab" data-content="justin">
											<h3 class="tab__header header--style-3" >Just in</h3>
										</div>
									</div>

									<div class="tabs__content" y-name="content justin">
										<?php view::recent(); ?>
									</div>

									<div class="tabs__content" y-name="content mostread" style="display: none">
										<?php view::mostread(); ?>
									</div>
								</section>
							</div>
						</section>
					</div>
				</aside>
			</div>
		</div>
	</div>
<?php view::end(); ?>