<?php
$bem = fetch::bem('article', $context ?? null, $modifier ?? null);
$section = fetch::section($item);
view::extend('template/default');
view::block('seo', fetch::seo('article', ['item' => $item]));
view::block('body.class', 'article-item');
view::block('billboard', fetch::banner('article_top'));
?>


<?php view::start('main') ?>

<?php /* view::file('contribute/modal'); */ ?>

<div class="container article-container">

	<div class="row-flex gutter-2">
		<div class="col">

			<article class="article article--<?php view::attr($section) ?>">
				<header class="article__header ">
					<h1><?php view::text($item->title) ?></h1>
					<h2><?php view::text($item->subtitle) ?></h2>
				</header>


				<div class="live-intro">
					<?php view::raw($item->summary); ?>
				</div>



				<div class="article__byline byline byline--style-2">
					<div class="row-flex">
						<div class="col">
							<div class="byline__details">


								<!-- <?php foreach ($item->authors as $author): ?>
									<?php if ($author->image): ?>
										<a
											href="<?php view::route('author', ['slug' => $author->slug]) ?>"
											title="<?php view::attr($author->title) ?>" class="byline__author__image-wrapper"
										>
											<img class="byline__author__image" src="<?php view::src($author->image, '320') ?>" />
										</a>
									<?php endif; ?>
								<?php endforeach; ?>

								<div class="byline__details__column">
									<div class="byline__author">
										<?php
										$authorTags = [];
										foreach ($item->authors as $author) {
											  $authorTags[] = '<a href="' . fetch::route('author', ['slug' => $author->slug]) . '" class="byline__author__name" title="' . fetch::attr($author->title) . '">' . fetch::text($author->title) . '</a>';
										}
										$authorHtml = '';
										$separator = 'By ';
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



									<time class="byline__datetime">
										<?php view::date($item->time_publication); ?>
									</time>

								</div> -->
							</div>
						</div>

						<div class="col-auto">
							<div class="byline__social">
								<?php view::file('common/social', ['item'=>$item]) ?>
							</div>
						</div>
					</div>
				</div>



				<?php /* if ($item->image): ?>
				<figure class="article__featured-image featured-image">
					<img src="<?php view::src($item->image, 'article_1360') ?>" />
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
				<?php endif; */ ?>


				<div class="live-posts" y-name="article-content" y-use="Live"
					data-url="<?php view::route('live', ['id' => $item->id, 'from' => '{{from}}']) ?>"
					data-prevurl="<?php view::route('live', ['id' => $item->id, 'from' => '{{from}}']) ?>">

					<script type="text/html" y-name="post">
					<div class="live-post"
						id="live-post-{{time}}"
						data-url="<?php view::route('live', ['id' => $item->id, 'from' => '{{from}}']) ?>"
						data-title="Ukraine Brief Entry - {{time | time}}">
						<time class="live-post__date timeago" datetime="{{time}}" y-use="Timeago">{{time | time}}</time>
						<div class="live-post__body" y-name="body"></div>
					</div>
					</script>


					<script type="text/html" y-name="block-html">
					<div class="article__block article__block--html live-block"></div>
					</script>


					<script type="text/html" y-name="block-article">
					<div class="article__block article__block--article live-block">
						<aside class="article__related-article">
							<a class="related-article__inner" href="{{ url }}" title="{{ title }}">
								<h3 class="related-article__title">
									{{ title }}
								</h3>
								<span class="related-article__cta">Read more</span>
							</a>
						</aside>
					</div>
					</script>

					<script type="text/html" y-name="block-image">
					<div class="article__block article__block--image live-block">
						<figure class="article__image">
							<img src="{{ src }}" />
						</figure>
						<figcaption>
							<span>{{ caption }}</span>
							<span>{{ credits }}</span>
						</figcaption>
					</div>
					</script>


					<script type="text/html" y-name="block-embed">
						<div class="article__block article__block--embed live-block">
							<div class="article__embed" y-name="embed"></div>
						</div>
					</script>

					<script type="text/html" y-name="block-link">
					<div class="article__block article__block--link live-block">
						<div class="article__link">
							<a href="{{ link.url }}" title="{{ link.title }}">
								{% if description %}
								<div class="article__link__description">
									{{ description }}
								</div>
								{% endif %}
								<h3 class="article__link__title">{{ link.title }}</h3>
							</a>
						</div>
					</div>
					</script>
				</div>

				<div id="load-next" class="live-loader">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
						y="0px" width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40"
						xml:space="preserve">
						<path opacity="0.2" fill="#000"
							d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
			  s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
			  c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z" />
						<path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
			  C22.32,8.481,24.301,9.057,26.013,10.047z">
							<animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20"
								to="360 20 20" dur="0.5s" repeatCount="indefinite" />
						</path>
					</svg>
				</div>


				<?php foreach ($item->authors as $author): ?>
				<?php if (($author->body != '') ||($author->twitter!='')): ?>
				<div class="hidden-sm-up">
					<a href="<?php view::route('author', ['slug' => $author->slug]) ?>"
						title="<?php view::attr($author->title) ?>">
						<?php view::file('author/excerpt/default', ['item' => $author, 'context'=>'article']); ?>
					</a>
				</div>
				<div class="hidden-xs">
					<?php view::file('author/excerpt/small', ['item' => $author, 'context'=>'article']); ?>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>


				<div class="article__bottom"></div>
				<?php if(count($item->tags)>0): ?>
				<div class="article__tags">
					Read more about:
					<?php $glue = ''; ?>
					<?php foreach ($item->tags as $tag): ?>
					<?php echo $glue; ?>
					<?php view::file('common/tag', ['item' => $tag, 'context'=>'article__tags']) ?>
					<?php $glue = ', '; ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>



				<?php view::file('common/social', ['item'=>$item]) ?>
			</article>
		</div>



		<div class="col-auto hidden-sm-down">
			<aside class="sidebar">
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

				<?php view::banner('article_sidebar_bottom') ?>

			</aside>
		</div>
	</div>

</div>


<?php view::banner('article_2') ?>
<?php view::banner('article_2_mobile') ?>

<div class="container">
	<section class="cluster">
		<div class="cluster__header">
			<h2 class="cluster__label header--style-3">
				<?php view::lang('Read more') ?>
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

<?php view::end(); ?>