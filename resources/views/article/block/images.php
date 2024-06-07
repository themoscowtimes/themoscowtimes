<div class="article__images">
	<?php if (false && isset($_GET["amp"]) == 1): ?>
		<amp-carousel height="250" layout="fixed-height" type="slides" role="region" aria-label="Basic usage carousel">
		<?php foreach ($block['images'] as $image): ?>
			<figure class="article__image">
				<amp-img src="<?php view::src($image, 'article_640-amp') ?>" layout="responsive" height="360" width="640"></amp-img>
				<?php if (isset($image['junction']) && ($image['junction']['caption'] || $image['junction']['credits']) ): ?>
				<figcaption class=""><span class="article__images__caption"><?php view::text($image['junction']['caption']) ?></span>
				<?php if ($image['junction']['credits']): ?>
				<span class="article__images__credits"><?php view::text($image['junction']['credits']) ?></span>
				<?php endif; ?>
				</figcaption>
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
		</amp-carousel>
	<?php else: ?>
	<div class="slider slider--fixed-size" y-use="Slider" data-fixed-size="true">
		<?php foreach ($block['images'] as $image): ?>
			<div class="slider__slide" y-name="slide">

				<a href="<?php view::src($image, '1360') ?>" y-name="lightbox" title="<?php if (isset($image['junction'])): ?><?php view::attr($image['junction']['caption']) ?>&nbsp;&nbsp;<?php view::text($image['junction']['credits']) ?><?php endif; ?>">
					<div class="slider__slide-inner ">
						<img src="<?php view::src($image, '1920') ?>"  />
					</div>
					<?php if (isset($image['junction']) && ($image['junction']['caption'] || $image['junction']['credits']) ): ?>
						<figcaption class="">
							<span class="article__images__caption"><?php view::text($image['junction']['caption']) ?></span>
							<?php if ($image['junction']['credits']): ?>
								<span class="article__images__credits"><?php view::text($image['junction']['credits']) ?></span>
							<?php endif; ?>
						</figcaption>
					<?php endif; ?>
				</a>
			</div>
		<?php endforeach; ?>
		<div class="slider__nav" y-name="navigation">
			<a class="slider__previous" y-name="previous"><i class="fa fa-chevron-left"></i></a>
			<a class="slider__next" y-name="next"><i class="fa fa-chevron-right"></i></a>
		</div>
	</div>
	<?php endif; ?>
</div>