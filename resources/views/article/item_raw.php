<?php
$bem = fetch::bem('article', $context ?? null, $modifier ?? null);
$archive = $archive ?? false;
$section = fetch::section($item);
?>

<?php
$authorsArr = [];
foreach($item['authors'] as $author) {
	$authorsArr[] = $author->title;
}

$sectionsArr = [];
foreach(fetch::config('sections') as $section => $label) {
	if($item->$section == 1) {
		$sectionsArr[] = $label;
	}
}

$sections = implode(', ', $sectionsArr);
$authors = implode(', ', $authorsArr);
?>


<div class="container--full" y-name="banner">
	<div class="preheader_advert py-2">
		<!-- Tag ID: themoscowtimes.com_header -->
		<div align="center" data-freestar-ad="__336x280 __970x250"
			id="themoscowtimes.com_header_<?php view::text($item->id); ?>">
		</div>
		<script data-cfasync="false" type="text/javascript">
		freestar.config.enabled_slots.push({
			placementName: "themoscowtimes.com_header",
			slotId: "themoscowtimes.com_header_<?php view::text($item->id); ?>"
		});
		</script>
	</div>
</div>
<?php /* view::banner('article_top'); */ ?>

<?php view::manager('article', $item->id); ?>

<hr class="container mb-5 mt-5" style="border-width: 1px; border-color: #e8e8e8;">

<div class="lazy-loaded article-container" id="article-id-<?php view::text($item->id); ?>"
	data-page-id="<?php view::text($item->id); ?>" data-next-id="<?php view::text($next_item_id); ?>"
	data-article-url="<?php view::route('article', $item->data()); ?>"
	data-article-title="<?php view::text($item->title);?>">

	<div class="gtm-section gtm-type" data-section="<?php view::attr($section) ?>"
		data-type="<?php view::attr($item->type) ?>">
		<!-- Google Tag Manager places Streamads based on these classes -->
	</div>

	<?php if($item->type == 'gallery'): ?>
	<?php view::file('article/gallery', ['item' => $item, 'archive' => $archive, 'next_item_id' => $next_item_id]); ?>
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
						<?php if (isset($item->partners) && is_array($item->partners) && count($item->partners) > 0 ): ?>
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


					<div class="article__byline byline <?php echo ($item->opinion)?'byline--opinion':'' ?> ">
						<div class="row-flex">
							<div class="col">
								<div class="byline__details">

									<?php if (! $archive): ?>
									<?php foreach ($item->authors as $author): ?>
									<?php if ($author->image): ?>
									<a href="<?php view::route('author', ['slug' => $author->slug]) ?>"
										title="<?php view::attr($author->title) ?>" class="byline__author__image-wrapper"><img
											class="byline__author__image" src="<?php view::src($author->image, '320') ?>" /></a>
									<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>

									<div class="byline__details__column">
										<div class="byline__author">
											<?php
												$authorTags = [];
												foreach ($item->authors as $author) {
													if (! $archive){
													  $authorTags[] = '<a href="' . fetch::route('author', ['slug' => $author->slug]) . '" class="byline__author__name" title="' . fetch::attr($author->title) . '">' . fetch::text($author->title) . '</a>';
													} else {
													   $authorTags[] = '<span class="byline__author__name">' . fetch::text($author->title) . '</a>';
													}
												}

												$authorHtml = '';
												$separator = $item->type == 'podcast' ? '' : 'By ';
												while($authorTag = array_shift($authorTags)) {
													$authorHtml .= $separator . $authorTag;
													if(count($authorTags) > 1) {
														$separator = ', ';
													} else {
														$separator = ' and ';
													}
												}
												view::raw($authorHtml);
												?>
										</div>


										<?php if (strtotime($item->updated) > (strtotime($item->time_publication) + (10 * 60))): ?>
										Updated: <time class="byline__datetime timeago"
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



					<?php if($item->type === 'podcast'): ?>
					<div class="mb-3">
						<?php view::raw($item->audio); ?>
					</div>
					<?php elseif ($item->image && $item->video == ''): ?>
					<figure class="article__featured-image featured-image">
						<img src="<?php view::src($item->image, 'article_1360', $archive) ?>" />
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
						<?php if (($author->body != '') ||($author->twitter!='')): ?>
						<div class="hidden-sm-up">
							<a class="" href="<?php view::route('author', ['slug' => $author->slug]) ?>"
								title="<?php view::attr($author->title) ?>">
								<?php view::file('author/excerpt/default', ['item' => $author, 'context'=>'article']); ?>
							</a>
						</div>
						<div class="hidden-xs">
							<?php view::file('author/excerpt/small', ['item' => $author, 'context'=>'article']); ?>
						</div>
						<?php endif; ?>

						<?php endforeach; ?>
						<?php endif; ?>

						<div class="article__bottom"></div>

						<?php /* view::file('article/block/newsletter'); */ ?>

						<?php if(count($item->tags)>0): ?>
						<div class="article__tags">
							Read more about:
							<?php $glue = ''; ?>
							<?php foreach ($item->tags as $tag): ?>
							<?php echo $glue; ?><?php view::file('common/tag', ['item' => $tag, 'context'=>'article__tags']) ?><?php $glue = ', '; ?>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>

						<div class="hidden-md-up">
							<?php if ($item->business): ?>
							<?php view::file('article/sponsor') ?>
							<?php endif; ?>
						</div>

						<?php view::file('common/social', ['item'=>$item]) ?>
					</div>
				</article>
			</div>


			<div class="col-auto hidden-sm-down">
				<aside class="sidebar" style="">

					<?php if ($item->business): ?>
					<?php view::file('article/sponsor') ?>
					<?php endif; ?>

					<!-- vaste breedte 336 -->
					<section class="sidebar__section" y-name="banner">
						<div class="banner" style="">
							<div class="banner-inner">
								<!-- Tag ID: themoscowtimes.com_sidebar_1 -->
								<div align="center" data-freestar-ad="__300x600"
									id="themoscowtimes.com_sidebar_1_<?php view::text($item->id); ?>">
								</div>
								<script data-cfasync="false" type="text/javascript">
								freestar.config.enabled_slots.push({
									placementName: "themoscowtimes.com_sidebar_1",
									slotId: "themoscowtimes.com_sidebar_1_<?php view::text($item->id); ?>"
								});
								</script>
							</div>
						</div>
					</section>

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
					<!-- Article Sidebar Bottom Banner Position  -->
					<?php view::banner('article_sidebar_bottom') ?>
					<!-- END Article Sidebar Bottom Banner Position  -->
				</aside>
			</div>
		</div>
	</div>

	<?php endif; ?>

	<div class="container container--full py-3 mb-4" style="background-color: #efefef;" y-name="banner">
		<div class="align-center" style="line-height: 0">
			<div style="max-width: 984px; margin: 0 auto;">
				<!-- Tag ID: themoscowtimes.com_billboard_bott -->
				<div align="center" data-freestar-ad="__336x280 __970x250"
					id="themoscowtimes.com_billboard_bott_<?php view::text($item->id); ?>"></div>
				<script data-cfasync="false" type="text/javascript">
				freestar.config.enabled_slots.push({
					placementName: "themoscowtimes.com_billboard_bott",
					slotId: "themoscowtimes.com_billboard_bott_<?php view::text($item->id); ?>"
				});
				</script>
			</div>
		</div>
	</div>

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

</div>