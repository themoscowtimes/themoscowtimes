<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('section', ['section' => 'podcasts','tag' => ''])) ?>

<?php view::block('body.class', 'podcast-index') ?>

<?php view::start('billboard') ?>
<?php view::banner('section_top') ?>
<?php view::end() ?>


<?php view::start('main') ?>
<div class="container">
	<div class="row-flex">
		<div class="col">
			<section class="cluster cluster--section-articles mb-4">

				<div class="row-flex">
					<?php foreach($authors as $author): ?>
					<div class="col-6 cluster">
						<div class="cluster podcast-cluster">
							<div class="podcast-author">
								<h3 class="header--style-3 mb-2"><?php view::text($author->title); ?></h3>
								<?php $podcastImage = $author->image ?>
								<?php if ($podcastImage): ?>
								<a href="<?php view::route('author', ['slug' => $author->slug]) ?>" title="<?php view::text($author->title) ?>">
									<div class="podcast-author__image-wrapper">
										<figure class="">
											<img src="<?php view::src($podcastImage) ?>">
										</figure>
									</div>
								</a>
								<?php endif; ?>
								<?php $podcastDesc = $author->body ?>
								<?php if ($podcastDesc): ?>
								<p><?php view::text($podcastDesc); ?></p>
								<?php endif; ?>
							</div>

							<div class="podcast-list">
								<div class="flex-grow: ">
									<?php foreach($author->articles as $item): ?>
									<div class="podcast-list__item">
										<?php //view::file('article/excerpt/default', ['item' => $item]); ?>
										<time class="label label--color-3"
											datetime="<?php view::text(date('c', strtotime($item->time_publication))); ?>" y-use="Timeago">
											<?php view::date(strtotime($item->time_publication)); ?>
										</time>
										<a href="<?php view::route('article', ['slug' => $item->slug]) ?>">
											<h5 class="header--style-5 mb-2">
												<?php view::text($item['title']) ?>
											</h5>
											<div class="">
												<?php view::text($item->excerpt);?>
											</div>
										</a>
									</div>

									<?php endforeach; ?>
								</div>

							</div>
							<div class="podcast-list__more">
								<a href="<?php view::route('author', ['slug' => $author->slug]) ?>"
									title="<?php view::text($author->title) ?>">Listen more</a>
							</div>
							<hr class="mb-4">

						</div>
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
			</aside>
		</div>
	</div>
</div>

<?php view::end(); ?>