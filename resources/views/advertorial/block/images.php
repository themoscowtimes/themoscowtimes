<div class="article__images">

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

</div>