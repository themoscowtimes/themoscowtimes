<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('archive')) ?>

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
					<h1 class="cluster__label header--style-3">Archived articles <?php view::date($year . '-' . $month . '-' . $day); ?></h1>
				</div>
				<div class="row-flex">
					<?php foreach ($items as $item): ?>
						<div class="col-4 col-12-md col-4-sm">
							<?php view::file('article/excerpt/default', ['item' => $item, 'label' => false, 'archive' => true]); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		</div>


		<div class="col-auto  hidden-sm-down">
			<aside class="sidebar" style="">
				<div class="mb-3">
					<h2 class="cluster__label header--style-3">&nbsp;</h2>
				</div>

				<!-- vaste breedte 336 -->
				<section class="sidebar__section">
					<?php view::banner('section_1') ?>
				</section>

				<div class="sidebar__sticky">
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
				</div>
			</aside>
		</div>
	</div>
</div>

<?php view::end(); ?>