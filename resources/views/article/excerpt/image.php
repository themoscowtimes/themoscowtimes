<?php
$preset = $preset ?? 'article_640';
$default = $default ?? ($item->type == 'video'  ? fetch::url('static') . 'img/article_video.jpg' : fetch::url('static') . 'img/article_default.jpg');
$archive = $archive ?? false;
?>


<?php if ($item->image): ?>
	<img src="<?php view::src($item->image, $preset, $archive) ?>" />
<?php elseif($item->type == 'video' && $item->video): ?>
	<?php
	$regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
	if(preg_match($regex, $item->video, $matches)) {
		$src = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
		$fallback = [
			'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg',
			'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg',
			'https://img.youtube.com/vi/' . $matches[1] . '/default.jpg',
			$default
		];
	} else {
		$src = [$default];
	}
	?>
	<img y-use="Image" data-src="<?php view::attr(json_encode($fallback)) ?>" />
<?php else: ?>
	<img src="<?php view::attr($default) ?>" />
<?php endif; ?>