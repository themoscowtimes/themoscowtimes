<section class="cluster carousel carousel-ambassadors">
  <header>
    <h3 class="cluster__label">Testimonials</h3>
    <div class="carousel-toggles-counter"></div>
  </header>
  <div y-use="CarouselAmbassadors">
  <?php foreach ($ambassadors as $item): ?>
    <div>
      <header>
        <picture>
          <img width="120" height="120" src="<?php view::url('base'); ?>image/640<?php view::text($item['image']['path']); ?><?php view::text($item['image']['file']); ?>" />
        </picture>
        <div class="card">
          <h4><?php view::text($item['title']); ?></h4>
          <p><?php view::text($item['subtitle']); ?></p>
        </div>
      </header>
      <p class="intro">
				<?php view::text($item['intro']); ?>
				<a style="color: #3263c0;" href="<?php view::url('base'); ?>ambassador/<?php view::text($item['slug']); ?>">Read more</a>
			</p>
			<?php /*
      <div class="show-more-wrapper">
        <a class="button button--color-3" href="<?php view::url('base'); ?>ambassador/<?php view::text($item['slug']); ?>" style="margin-top: 24px;">Read more</a>
      </div>
			*/ ?>
    </div>
  <?php endforeach; ?>
  </div>
</section>