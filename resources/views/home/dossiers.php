<?php foreach ($items as $item): ?>
<?php if ($item && $item->location == $location): ?>
<?php echo view::raw($wrap[0]); ?>

<?php
		$regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
		$yt = false;
		if(preg_match($regex, $item->video, $matches)) {
			$yt = $matches[1];
		}
		?>

<div class="developing-story-container <?php view::attr($yt ? 'developing-story--video' : '') ?>">

  <div class="developing-story__header">
    <a href="<?php view::route('dossier', ['slug' => $item->slug]); ?>">
      <?php if($yt): ?>
      <div class="fluid-width-video-wrapper " style="padding-top: calc(9/16*100%);">
        <iframe src="https://www.youtube.com/embed/<?php view::attr($yt) ?>?&showinfo=0&rel=0&autoplay=1&rel=0"
          autoplay="1" rel="0" frameborder="0" allow="autoplay;" allowfullscreen>
        </iframe>
      </div>
      <?php elseif($item->image): ?>

			<?php if ($location == 1): ?>
			<div
				data-collection="<?php view::text($item->title); ?>"
				data-collection-url="<?php view::url('base'); ?>all-about/<?php view::text($item->slug) ?>"
				class="developing-story__header__image-wrapper slider-wrapper" y-name="lead-carousel" style="line-height: 0;">
        <figure>
          <img src="<?php view::src($item->image, 'article_1360') ?>" />
				</figure>
				<?php foreach ($item->articles as $index => $article): ?>
				<?php if ($index >= 4) break; ?>
				<?php if ($article->image):?>
				<figure class="lead-carousel-slide" data-url="<?php view::route('article', $article->data()) ?>" data-title="<?php view::text($article->title); ?>" y-name="lead-carousel-slide">
					<img src="<?php view::src($article->image, 'article_1360') ?>" />
				</figure>
				<?php endif; ?>
				<?php endforeach; ?>
      </div>
			<?php else: ?>
			<div class="developing-story__header__image-wrapper">
        <figure>
          <img src="<?php view::src($item->image, 'article_1360') ?>" />
				</figure>
      </div>
			<?php endif; ?>

			<?php endif; ?>

      <?php if ($item->label): ?>
      <span class="label developing-story__header__label label--opinion developing-story__header__label--opinion">
        <?php view::text($item->label); ?>
      </span>
      <?php endif; ?>

      <?php if(!$yt): ?>
      <div class="developing-story__header__content">
        <h3 class="developing-story__header__headline">
					<?php if ($item->title_live): ?>
						<!-- LIVE Label -->
						<span class="live-label-wrap">
							<span class="pulsating-icon"></span>
							<span>LIVE |</span>
						</span>
					<?php endif; ?>
					<?php view::text($item->title); ?>
				</h3>
        <?php if ($item->subtitle): ?>
        <br />
        <div class="developing-story__header__teaser">
          <?php view::text($item->subtitle); ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </a>
  </div>


  <div class="developing-story-articles">
    <div class="row-flex gutter-0">
			<?php foreach ($item->articles as $index => $article): ?>
      <?php if ($index >= 4) break; ?>
      <div class="col">
        <div class="developing-story-article <?php view::attr(($index == 0 )?'developing-story-article--first':'') ?>">
          <a href="<?php view::route('article', $article->data()) ?>">
            <div class="developing-story-article__inner">
              <time class="developing-story-article__time  "
                datetime="<?php view::text(date('c', strtotime($article->time_publication))); ?>" y-use="Timeago">
                <?php view::date(strtotime($article->time_publication)); ?>
              </time>
              <h5 class="developing-story-article__headline">
                <?php view::text($article->title); ?>
              </h5>
            </div>
          </a>
        </div>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</div>

<?php echo view::raw($wrap[1]); ?>
<?php endif; ?>
<?php endforeach; ?>