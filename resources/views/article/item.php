<?php
$bem = fetch::bem('article', $context ?? null, $modifier ?? null);
$archive = $archive ?? false;

$authorsArr = [];
foreach ($item['authors'] as $author) {
	$authorsArr[] = $author->title;
}

$sectionsArr = [];
foreach (fetch::config('sections') as $section => $label) {
	if ($item->$section == 1) {
		$sectionsArr[] = $label;
	}
}
$sections = implode(', ', $sectionsArr);
$authors = implode(', ', $authorsArr);
$section = fetch::section($item);

//do not load this in dev mode
if(fetch::env('env') != 'development'){
	view::asset('js', 'https://cdn.flipboard.com/web/buttons/js/flbuttons.min.js');
}

view::extend('template/default');

view::block('seo', fetch::seo('article', ['item' => $item, 'archive' => $archive]));
if ($item->type == 'video' || $item->type == 'gallery'){
	view::block('body.class', 'article-item article-item--full-header');
} else {
	view::block('body.class', 'article-item');
}
// Article above website
view::block('billboard', fetch::banner('article_top'));

view::start('main');
?>

<?php /* view::file('contribute/modal'); */ ?>

<article y-use="article.IsIntersecting">

	<?php view::manager('article', $item->id); ?>

	<div class="gtm-section gtm-type" data-section="<?php view::attr($section) ?>"
		data-type="<?php view::attr($item->type) ?>">
		<!-- Google Tag Manager places Streamads based on these classes -->
	</div>

	<?php if ($item->type == 'gallery'): ?>
	<?php view::file('article/gallery', ['item' => $item, 'archive' => $archive, 'next_item_id' => $next_item_id]); ?>
	<?php else: ?>
	<div class="container article-container" id="article-id-<?php view::text($item->id); ?>"
		data-page-id="<?php view::text($item->id); ?>" data-next-id="<?php view::text($next_item_id); ?>"
		data-article-url="<?php view::route($archive ? 'archive_article' : 'article', $item->data()); ?>"
		data-article-title="<?php view::text($item->title); ?>">
		<?php if ($item->type == 'video'): ?>
		<?php
				$regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
				$video = false;
				if (preg_match($regex, $item->video, $matches)) {
					$video = $matches[1];
					$embed = 'https://www.youtube.com/embed/' . $matches[1];
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
				<iframe
					src="https://www.youtube.com/embed/<?php view::attr($video) ?>?autoplay=1&loop=1&rel=0&wmode=transparent"
					allowfullscreen="" wmode="Opaque" width="100%" height="100%" frameborder="0"></iframe>
			</div>
		</figure>
		<?php endif; ?>
		<?php endif; ?>


		<div class="row-flex gutter-2">
			<div class="col">
				<?php if ($item->sponsored): ?>
				<?php view::file('article/sponsor/mt_plus', ['item' => $item]); ?>
				<?php endif; ?>
				<article class="article article--<?php view::attr($section) ?>">
					<header class="article__header ">
						<?php if ($item->opinion || $item->analysis): ?>
						<?php view::file('common/label', ['item' => $item, 'context' => $bem->block()]) ?>
						<?php endif; ?>
						<?php if (isset($item->partners) && is_array($item->partners) && count($item->partners) > 0): ?>
						<?php foreach($item->partners as $partner): ?>
						<span class="label article__label label--partner">
							Partner Content
						</span>
						<a class="label article__label label--partners"
							href="<?php view::route('partner', ['slug' => $partner->slug]) ?>"
							title="<?php view::attr($partner->title) ?>">
							<?php view::text($partner->title); ?>
						</a>
						<?php endforeach; ?>
						<?php endif; ?>
						<h1><a href="<?php view::route('article', $item->data()); ?>"><?php view::text($item->title) ?></a>
						</h1>
						<h2><?php view::text($item->subtitle) ?></h2>
					</header>

					<div class="article__byline byline <?php echo ($item->opinion) ? 'byline--opinion' : '' ?> ">
						<div class="row-flex">
							<div class="col">
								<div class="byline__details">

									<?php if (!$archive): ?>
									<?php foreach ($item->authors as $author): ?>
									<?php if ($author->image): ?>
									<a href="<?php view::route('author', ['slug' => $author->slug]) ?>"
										title="<?php view::attr($author->title) ?>" class="byline__author__image-wrapper">
										<img class="byline__author__image" src="<?php view::src($author->image, 'thumb') ?>" />
									</a>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>

									<div class="byline__details__column">
										<div class="byline__author">
											<?php
												$authorTags = [];
												foreach ($item->authors as $author) {
													if (!$archive) {
														$authorTags[] = '<a href="' . fetch::route('author', ['slug' => $author->slug]) . '" class="byline__author__name" title="' . fetch::attr($author->title) . '">' . fetch::text($author->title) . '</a>';
													} else {
														$authorTags[] = '<span class="byline__author__name">' . fetch::text($author->title) . '</span>';
													}
												}
												$authorHtml = '';
												$separator = $item->type == 'podcast' ? '' : 'By ';
												while ($authorTag = array_shift($authorTags)) {
													$authorHtml .= $separator . $authorTag;
													if (count($authorTags) > 1) {
														$separator = ', ';
													} else {
														$separator = ' and ';
													}
												}
												view::raw($authorHtml);
												?>
										</div>


										<?php if (strtotime($item->updated) > (strtotime($item->time_publication) + (10 * 60))): ?>
										Updated:
										<time class="byline__datetime timeago"
											datetime="<?php view::text(date('c', strtotime($item->updated))); ?>" y-use="Timeago">
											<?php view::date($item->updated); ?>
										</time>
										<?php else: ?>
										<time class="byline__datetime timeago"
											datetime="<?php view::text(date('c', strtotime($item->time_publication))); ?>" y-use="Timeago">
											<?php view::date($item->time_publication); ?>
										</time>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<div class="col-auto">
								<div class="byline__social">
									<?php view::file('common/social', ['item' => $item]) ?>
								</div>
							</div>
						</div>
					</div>

					<?php if ($item->intro != ''): ?>
					<div class="article__intro">
						<?php view::raw(nl2br(strip_tags($item->intro))); ?>
					</div>
					<?php endif; ?>

					<?php if ($item->type === 'podcast'): ?>
					<div class="mb-3">
						<?php view::raw($item->audio); ?>
					</div>
					<?php elseif ($item->image && $item->video == ''): ?>
					<figure class="article__featured-image featured-image">
						<img src="<?php view::src($item->image, 'article_1360', $archive) ?>" />
						<?php if ($item->caption != '' || $item->credits != ''): ?>
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


					<div class="article__content-container">
						<div class="article__content" y-name="article-content">
							<?php if (is_array($item->body)): ?>
							<?php foreach ($item->body as $index => $block): ?>
							<div data-id="article-block-type"
								class="article__block article__block--<?php view::attr($block['type']); ?> article__block--<?php echo $block['position'] ?? 'column' ?> ">
								<?php view::file('article/block/' . $block['type'], ['block' => $block]) ?>
								<?php if ($index == 1): ?>
								<?php view::banner('article_body') ?>
								<?php view::banner('article_body_mobile') ?>
								<?php endif; ?>
							</div>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>

						<?php if ($item->opinion): ?>
						<div class="article__disclaimer">
							The views expressed in opinion pieces do not necessarily reflect the position of The Moscow Times.
						</div>
						<?php endif; ?>

						<?php if (!$archive && $item->opinion): ?>
						<?php foreach ($item->authors as $author): ?>
						<?php if (($author->body != '') || ($author->twitter != '')): ?>
						<div class="hidden-sm-up">
							<a class="" href="<?php view::route('author', ['slug' => $author->slug]) ?>"
								title="<?php view::attr($author->title) ?>">
								<?php view::file('author/excerpt/default', ['item' => $author, 'context' => 'article']); ?>
							</a>
						</div>
						<div class="hidden-xs">
							<?php view::file('author/excerpt/small', ['item' => $author, 'context' => 'article']); ?>
						</div>
						<?php endif; ?>
						<?php endforeach; ?>
						<?php endif; ?>

						<div class="article__bottom">

						</div>

						<?php /* view::file('article/block/newsletter'); */ ?>

						<?php if (count($item->tags) > 0): ?>
						<div class="article__tags">
							Read more about:
							<?php $glue = ''; ?>
							<?php foreach ($item->tags as $tag): ?>
							<?php echo $glue; ?><?php view::file('common/tag', ['item' => $tag, 'context' => 'article__tags']) ?><?php $glue = ', '; ?>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>

						<div class="hidden-md-up">
							<?php if ($item->business): ?>
							<?php view::file('article/sponsor') ?>
							<?php endif; ?>
						</div>

						
						<?php view::file('contribute/banner-one-dollar') ?>
						<?php view::file('common/social', ['item' => $item]) ?>
					</div>

				</article>
			</div>


			<div class="col-auto hidden-sm-down">
				<aside class="sidebar">

					<?php if ($item->business): ?>
					<?php view::file('article/sponsor') ?>
					<?php endif; ?>

					<!-- Article sidebar -->
					<?php view::banner('article_1') ?>

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
					<!-- Article sidebar bottom -->
					<?php view::banner('article_sidebar_bottom') ?>

				</aside>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- Article billboard bottom -->
	<?php view::banner('article_2') ?>
	<!-- Article billboard bottom -->
	<?php view::banner('article_2_mobile') ?>


	<div class="container">
		<section class="cluster">

			<div class="cluster__header">
				<h2 class="cluster__label header--style-3">
					<?php if ($item->type == 'gallery'): ?>
					<a href="<?php view::route('gallery') ?>"
						title="<?php view::lang('More galleries') ?>"><?php view::lang('More image galleries') ?></a>
					<?php elseif ($item->type == 'video'): ?>
					<a href="<?php view::route('video') ?>"
						title="<?php view::lang('More videos') ?>"><?php view::lang('More videos') ?></a>
					<?php elseif ($item->type == 'podcast'): ?>
					<a href="<?php view::route('podcasts') ?>"
						title="<?php view::lang('More podcasts') ?>"><?php view::lang('More podcasts') ?></a>
					<?php else: ?>
					<?php view::lang('Read more') ?>
					<?php endif; ?>
				</h2>
			</div>

			<div class="row-flex">
				<?php foreach ($related as $relatedItem): ?>
				<div class="col-3 col-6-sm">
					<?php view::file('article/excerpt/default', ['item' => $relatedItem]); ?>
				</div>
				<?php endforeach; ?>
			</div>
		</section>
	</div>

	<?php /* view::file('contribute/bar'); */ ?>
	<!-- sticky_article_billboard_bottom -->
	<?php view::banner('sticky_article_billboard_bottom') ?>



</article>

<?php if (!is_null($next_item_id)): ?>
<div class="container next-article-loader" id="load-next-article" y-use="article.InfiniteScroll"
	data-id="<?php view::text($next_item_id); ?>" data-url="<?php view::route('scroll_load', ['id' => '{{id}}']) ?>">
	<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
		<path opacity="0.2" fill="#000"
			d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
			  s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
			  c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z" />
		<path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
			  C22.32,8.481,24.301,9.057,26.013,10.047z">
			<animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20"
				dur="0.5s" repeatCount="indefinite" />
		</path>
	</svg>
</div>
<?php endif; ?>

<script>
if (typeof window.freestar === 'object') {
	freestar.config.disabledProducts = {
		sideWall: true,
	};
}
</script>

<?php view::end(); ?>