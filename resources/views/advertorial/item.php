<?php
$bem = fetch::bem('article', $context ?? null, $modifier ?? null);
?>


<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('advertorial', ['item' => $item])) ?>

<?php if ( $item->type == 'video' || $item->type == 'gallery'): ?>
	<?php view::block('body.class', 'article-item article-item--full-header') ?>
<?php else: ?>
	<?php view::block('body.class', 'article-item') ?>
<?php endif; ?>


<?php view::start('billboard') ?>
	<?php view::banner('advertorial_top') ?>
<?php view::end() ?>


<?php view::start('main') ?>

	<?php view::manager('advertorial', $item->id); ?>

	<?php if($item->type == 'gallery'): ?>
		<?php view::file('advertorial/gallery', [
			'item' => $item,
		]); ?>
	<?php else: ?>
		<div class="container">
			<?php if($item->type == 'video'): ?>
				<?php
				$regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
				$video = false;
				if(preg_match($regex, $item->video, $matches)) {
					$video = $matches[1];
					$embed  = 'https://www.youtube.com/embed/' . $matches[1];
					$poster = [
						'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg',
						'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg',
						'https://img.youtube.com/vi/' . $matches[1] . '/default.jpg',
					];
				}
				?>

				<?php if ($video): ?>
					<figure class="videoheader" y-use="Youtube" data-video="<?php view::attr($video) ?>">
						<div class="videoplayer__aspect"></div>
						<div class="videoplayer" y-name="player">
							<iframe src="https://www.youtube.com/embed/<?php view::attr($video) ?>?autoplay=1&loop=1&rel=0&wmode=transparent" allowfullscreen="" wmode="Opaque" width="100%" height="100%" frameborder="0"></iframe>
						</div>
					</figure>
				<?php endif; ?>
			<?php endif; ?>


			<div class="row-flex gutter-2">
				<div class="col">
					<div class="campaign__header campaign__header--style-2">
						<div class="campaign__plus campaign__plus--advertorial">
							<div class="plus__wrapper plus">
								MT<sup class="plus__sub">+</sup>
							</div>
						</div>
						<div class="campaign__disclaimer">
							<strong>MT<sup>+</sup> is branded content of The Moscow Times.</strong><br/>This content is provided by outside parties.
						</div>
					</div>
					<article class="article article--advertorial">

						<?php if ($item->image && $item->video == ''): ?>
							<figure class="article__featured-image featured-image" >
								<img src="<?php view::src($item->image, 'article_1360') ?>" />
								<?php if ($item->caption!='' || $item->credits!=''): ?>
									<figcaption class="">
										<span class="article__featured-image__caption featured-image__caption">
											<?php view::text($item->caption) ?>
										</span>
										<span class="article__featured-image__credits featured-image__credits">
											<?php view::text($item->credits) ?>
											</span>
									</figcaption>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<header class="article__header ">
							<h1><?php view::text($item->title) ?></h1>
							<h2><?php view::text($item->subtitle) ?></h2>
						</header>


						<div class="article__byline byline byline--advertorial">
							<div class="row-flex">
								<div class="col">
									<div class="byline__details">

										<?php if ($campaign->logo): ?>
											<a href="<?php view::route('campaign', ['slug' => $campaign->slug]); ?>" title="<?php view::text($campaign->title) ?>" class="byline__logo" >
												<img class="byline__logo__img" src="<?php view::src($campaign->logo, '320') ?>" title="<?php view::text($campaign->title ? $campaign->title :'') ?>" />
											</a>
										<?php endif; ?>

										<div class="byline__details__column">
											<?php if ($item->campaign->title != ''): ?>
												<div class="byline__author">
													<?php view::lang('Brought to you by'); ?>
													<a href="<?php view::route('campaign', ['slug' => $campaign->slug]); ?>" title="<?php view::text($campaign->title) ?> " class="byline__author__name">
														<?php view::text($campaign->title) ?>
													</a>
												</div>
											<?php endif; ?>

											<?php if (strtotime($item->updated) > (strtotime($item->time_publication) + (10 * 60))): ?>
												Updated: <time class="byline__datetime timeago" datetime="<?php view::text(date('c', strtotime($item->updated))); ?>" y-use="Timeago">
													<?php view::date($item->updated); ?>
												</time>

											<?php else: ?>
												<time class="byline__datetime timeago" datetime="<?php view::text(date('c', strtotime($item->time_publication))); ?>" y-use="Timeago">
													<?php view::date($item->time_publication); ?>
												</time>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="col-auto" >
									<div class="byline__social">
									<?php view::file('common/social', ['item'=>$item]) ?>
									</div>
								</div>
							</div>
						</div>

						<?php if ($item->intro!=''): ?>
							<div class="article__intro">
								<?php view::raw(nl2br(strip_tags($item->intro))); ?>
							</div>
						<?php endif; ?>

						<div class="article__content-container">
							<div class="article__content" y-name="article-content">
								<?php if (is_array($item->body)): ?>
									<?php foreach ($item->body as $index => $block): ?>
										<div class="article__block article__block--<?php view::attr($block['type']); ?> article__block--<?php echo $block['position'] ?? 'column' ?> ">
											<?php view::file('article/block/' . $block['type'], ['block' => $block]) ?>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>

							</div>

							<div class="article__bottom"></div>


						</div>
					</article>
				</div>


				<div class="col-auto">
					<aside class="sidebar" style="">
						<h3 class="header--style-3 mb-2">
							<?php view::lang('More from:'); ?>
							<?php view::text($item->campaign->title) ?>
						</h3>

						<?php foreach ($advertorials as $advertorial): ?>
							<?php view::file('advertorial/excerpt/default', [
								'item' => $advertorial,
								'campaign' => $campaign,
								'modifier' => 'small',
							]); ?>
						<?php endforeach; ?>

						<?php view::banner('advertorial_1') ?>
					</aside>

				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php view::banner('advertorial_2') ?>
	<?php view::banner('advertorial_2_mobile') ?>

<?php view::end(); ?>
