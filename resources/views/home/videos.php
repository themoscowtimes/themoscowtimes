<!-- Videos Gallery -->
<?php
  $regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
  $video_id = false;
  if(preg_match($regex, $videos[0]->video, $matches)) {
    $video_id = $matches[1];
  }
?>

<div class="videos-gallery" y-use="Videogallery">
  <div class="videos-gallery__list">
    <?php if ($video_id): ?>
    <figure class="videoheader">
      <div class="videoplayer__aspect"></div>
      <div class="videoplayer" y-name="video-player">
        <iframe y-name="iframe" data-id="<?php view::attr($video_id) ?>" id="video-<?php view::attr($video_id) ?>"
          src="https://www.youtube.com/embed/<?php view::attr($video_id) ?>?enablejsapi=1&autoplay=0&loop=0&controls=1&rel=0&wmode=transparent"
          allowfullscreen="" wmode="Opaque" width="100%" height="100%" frameborder="0"></iframe>
      </div>
    </figure>
    <?php endif; ?>
  </div>
  <div class="videos-gallery__keys">
    <ul>
      <?php foreach ($videos as $key => $item): ?>
      <?php
          $regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
          $video_id = false;
          if(preg_match($regex, $item->video, $matches)) {
            $video_id = $matches[1];
          }
        ?>
      <li y-name="player-toggle" data-id="<?php view::attr($video_id) ?>" class="player-toggle">
        <img src="<?php view::attr('https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg') ?>" alt="poster" />
        <div class="article-excerpt-default__type-icon">
          <i class="fa fa-play"></i>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<!-- END Videos Gallery -->