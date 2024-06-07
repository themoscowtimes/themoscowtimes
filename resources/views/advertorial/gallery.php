<?php
$bem = fetch::bem('article', $context ?? null, $modifier ?? null);
$archive = $archive ?? false;
?>

<?php if ($item->image): ?>
	<div class="container--full">
		<div class="galleryheader">
			<picture class="galleryheader__image">
				<img src="<?php view::src($item->image, '1920', $archive) ?>" />

			</picture>

		</div>
		<?php if ($item->caption || $item->credits): ?>
			<div class="galleryheader__caption-container">
				<?php if ($item->caption): ?>
					<div class="galleryheader__caption">
						<?php view::text($item->caption); ?>
					</div>
				<?php endif; ?>

				<?php if ($item->credits): ?>
					<div class="galleryheader__credits">
						<?php view::text($item->credits); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div>
<?php endif; ?>

<div class="container">
	<article class="article">
		<div class="article__content-container">
			<div class="article__content">

				<header class="article__header">
					<h1><?php view::text($item->title) ?></h1>
					<h2><?php view::text($item->subtitle) ?></h2>
				</header>

				<div class="article__byline byline">
					<div class="row-flex">
						<div class="col">
							<div class="byline__details" style="">
								<div class="byline__details__column">
									<time class="byline__datetime timeago" datetime="<?php view::text($item->time_publication); ?>" y-use="Timeago">
										<?php view::date($item->time_publication, true); ?>
									</time>
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

				<div class="article__block article__block--full article__gallery">
					<?php foreach($item->images as $image): ?>

						<div class="article__gallery__item">
							<img src="<?php view::src($image, '1360', $archive) ?>" />

							<?php if ($image->junction('title') != '' || $image->junction('caption') != '' || $image->junction('credits') != ''): ?>
								<div class="gallery__item__aside">
									<div class="gallery__item__aside__content">
										<h6 class="gallery__item__aside__content__title"><?php view::text($image->junction('title')); ?></h6>
										<div class="gallery__item__aside__content__caption">
											<?php view::raw(nl2br(strip_tags($image->junction('caption')))); ?>
										</div>
										<div class="gallery__item__aside__content__credits">
											<?php view::text($image->junction('credits')); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>


			<div class="article__content">
				<?php if (is_array($item->body)): ?>
					<?php foreach ($item->body as $block): ?>
						<div class="article__block article__block--<?php echo $block['type']; ?> article__block--<?php echo $block['position'] ?? 'column' ?> ">
							<?php view::file('article/block/' . $block['type'], ['block' => $block]) ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<?php view::file('common/social', ['item'=>$item]) ?>
		</div>
	</article>
</div>