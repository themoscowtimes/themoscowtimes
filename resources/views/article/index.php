<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('section', ['section' => $section,'tag' => $tag])) ?>

<?php view::block('body.class', 'article-index') ?>

<?php view::start('billboard') ?>
<?php view::banner('section_top') ?>
<?php view::end() ?>


<?php view::start('main') ?>

<div class="container">
	<div class="row-flex">
		<div class="col">
			<section class="cluster cluster--section-articles mb-4">

				<div class="cluster__header">
					<?php /* if ($section == 'tag' && $tag == 'regions'): ?>
						<?php view::file('article/map/index', []); ?>
					<?php endif; */ ?>
					<?php if ($section == 'tag'): ?>
						<h1 class="cluster__label"><?php view::lang('Articles with tag'); ?> "<?php view::text($tag); ?>"</h1>
					<?php elseif ($section == 'sponsored'): ?>
						<?php view::file('article/sponsor/mt_plus', []); ?>
					<?php else: ?>

						<h1 class="cluster__label"><?php view::lang('section.' . $section); ?></h1>
						<?php if ($section == 'lecture_series'): ?>
							<div style="padding: 48px 0;">
								<p>The TMT Lecture Series is a joint project between The Moscow Times and top global universities that sees
									leading Russian journalists forced to emigrate after the invasion of Ukraine give lectures about what is
									going on inside Russia.</p>
								<ul class="lectures__partners">
									<li><img src="<?php view::url('static'); ?>img/partner_ucl.jpg" alt="UCL University College London" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_tufts.png" alt="Tufts" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_washington.jpg" alt="GW University" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_amherst.png" alt="Amherst College" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_hague.jpg" alt="The Hague University of Applied Sciences" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_bologna.jpg" alt="" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_muni.jpg" alt="" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_maastricht.jpg" alt="" /></li>
									<li class="lectures__shim"><img src="<?php view::url('static'); ?>img/partner_amsterdam.jpg" alt="Universirty of Amsterdam" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_leiden.jpg" alt="" /></li>
									<li><img src="<?php view::url('static'); ?>img/partner_kings_college_london.jpg" alt="Kings College London" /></li>
								</ul>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="row-flex">
					<?php $index = 0 ?>
					<?php foreach ($items as $item): ?>
						<?php
						if($index === 0){
							$view = 'article/excerpt/lead';
							$primary = true;
							$col = 'col-12 col-12-md col-12-sm';
							$image = null;
							if ($section == 'lecture_series') {
								$view = 'article/excerpt/default';
								$image = 'article_640';
								$col = 'col-4 col-12-md col-4-sm';
							}
						} else {
							$view = 'article/excerpt/default';
							$primary = false;
							if($index - 4 >= 0 && ($index - 4) % 5 === 0) {
								$image = 'article_960';
								$col = 'col-8 col-12-md col-4-sm';
							} else {
								$image = 'article_640';
								$col = 'col-4 col-12-md col-4-sm';
							}
						}
						$index++;
						?>
						<div class="<?php view::attr($col) ?>">
							<?php view::file($view, [
								'item' => $item,
								'modifier' => $primary ? 'primary' : null,
								'label' =>  $section == 'opinion' ||  $section == 'indepth'  ? '' : true,
								'image' => $image
							]); ?>
						</div>
					<?php endforeach; ?>

					<div class="col-12" y-use="More"
						data-url="<?php view::route($section, ['tag' => $tag, 'offset' => '{{offset}}']) ?>" data-start="19"
						data-step="18">
						<div class="align-center">
							<span title="View more articles" class="button">View more articles</span>
						</div>
					</div>
				</div>
			</section>
		</div>


		<div class="col-auto  hidden-sm-down">
			<aside class="sidebar" style="">
				<div class="mb-3">
					<h2 class="cluster__label header--style-3">&nbsp;</h2>
				</div>

				<?php if ($section == 'business'): ?>
					<?php view::file('article/sponsor/business') ?>
				<?php endif; ?>

				<?php if ($section == 'indepth'): ?>
				<?php view::file('article/sponsor/indepth') ?>
					<?php endif; ?>

				<?php if ($section == 'living'): ?>

					<?php view::file('article/sponsor/living') ?>
					<?php /* ?>
					<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
					<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
					<script type="module" src="<?php view::url('static'); ?>js/vue/main.js"></script>
					<template>
						<div>
							<!--<span v-html="piet"></span>-->
						</div>
					</template>


					<?php */ ?>

					<?php view::file('article/excerpt/bite') ?>

				<?php endif; ?>




				<!-- vaste breedte 336 -->
				<section class="sidebar__section">
					<?php view::banner('section_1') ?>
				</section>




				<?php if ($section !== 'living'): ?>
					<div class="sidebar__sticky">
						<div class="tabs" y-use="Tabs" data-active="tabs__tab--active">
							<section class="sidebar__section">
								<div class="sidebar__section__header">
									<div class="tabs__tab" y-name="tab" data-content="mostread">
										<h3 class="tab__header header--style-3">Most read</h3>
									</div>
									<div class="tabs__tab" y-name="tab" data-content="justin">
										<h3 class="tab__header header--style-3">Just in</h3>
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
					</div>
				<?php endif; ?>

			</aside>
		</div>
	</div>
</div>

<script>
if (typeof window.freestar === 'object') {
	freestar.config.disabledProducts = {
		sideWall: true,
	};
}
</script>

<?php view::end(); ?>