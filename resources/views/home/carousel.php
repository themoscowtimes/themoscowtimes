<div class="carousel" y-use="Carousel">

  <?php /*
	$regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
	if($video && preg_match($regex, $video->video, $matches)) {
		$yt = $matches[1];
		$posters = [
			'https://img.youtube.com/vi/' . $yt . '/maxresdefault.jpg',
			'https://img.youtube.com/vi/' . $yt . '/hqdefault.jpg',
			'https://img.youtube.com/vi/' . $yt . '/default.jpg',
		];
	} else {
		$yt = false;
	}
	*/ ?>
  <button y-name="prev" class="carousel__toggle carousel__toggle--prev" id="homepage-carousel-toggle-prev">&lt;</button>
  <button y-name="next" class="carousel__toggle carousel__toggle--next" id="homepage-carousel-toggle-next">&gt;</button>


  <!-- <div class="carousel__pane" y-name="pane">
    <div class="sidebar__section__header">
      <h3 class="sidebar__section__label header--style-3"><i class="fa fa-microphone"></i>
        <?php view::lang('Latest Podcast'); ?></h3>
    </div>
    <div class="podcast-excerpt-default">
      <a class="podcast-excerpt-default__link" href="<?php view::route('article', $podcast->data()) ?>">
        <?php
				$podcastImage = null;
				foreach($podcast->authors as $author) {
					if($podcastImage = $author->image) {
						break;
					}
				}
				?>

        <?php if ($podcastImage): ?>
        <div class="podcast-excerpt-default__image-wrapper">
          <figure>
            <img src="<?php view::src($podcastImage) ?>">
          </figure>
        </div>
        <?php endif; ?>

        <span class="label label--color-3">
          <?php view::date($podcast->time_publication) ?>
        </span>

        <div class="podcast-excerpt-default__headline">
          <?php view::text($podcast->title)?>
        </div>

        <?php if ($podcast->excerpt):?>
        <div class="podcast-excerpt-default__teaser">
          <?php view::text($podcast->excerpt)?>
        </div>
        <?php endif; ?>
      </a>
    </div>
  </div> -->

  <?php if ($feature): ?>
  <div class="carousel__pane" y-name="pane">
    <div class="sidebar__section__header">
      <h3 class="sidebar__section__label header--style-3"><?php view::lang('Editor\'s Pick'); ?></h3>
    </div>
    <?php view::file('article/excerpt/default', ['item' => $feature]); ?>
  </div>
  <?php endif; ?>

  <?php if ($pick): ?>
  <div class="carousel__pane" y-name="pane">
    <div class="sidebar__section__header">
      <h3 class="sidebar__section__label header--style-3"><?php view::lang('Editor\'s Pick'); ?></h3>
    </div>
    <?php view::file('article/excerpt/default', ['item' => $pick]); ?>
  </div>
  <?php endif; ?>
</div>