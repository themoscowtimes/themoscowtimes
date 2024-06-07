<?php
view::extend('template/home');

view::asset('css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css');
view::asset('js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js');

view::block('seo', fetch::seo('home'));
view::block('body.class', 'home');

if ($banner = fetch::banner('home_top')) {
	view::block('billboard', $banner);
}

view::start('main');
?>

<div class="container">
	<div class="row-flex">
		<div class="col">
			<section class="cluster cluster--section-today ">
				<div class="row-flex">

					<?php view::file('home/dossiers', [
						'items' => $dossiers,
						'location' => 1,
						'wrap' => ['<div class="container lead-collection" y-use="home.CollectionCarousel">', '</div>']
					]) ?>

					<div class="col-8 col-12-sm-down">
						<?php if ($article = array_shift($highlights)): ?>
						<?php view::file('article/excerpt/primary', ['item' => $article]); ?>
						<?php endif; ?>
					</div>

					<div class="col-4 col-12-sm-down">
						<div class="row-flex">
							<?php for($i = 0; $i < 2; $i++): ?>
							<?php if ($article = array_shift($highlights)): ?>
							<div class="col-12 col-6-sm-down">
								<?php view::raw($i > 0 ? '<hr class="article-excerpt-secondary__line"/>' : '') ?>
								<?php view::file('article/excerpt/secondary', ['item' => $article]); ?>
							</div>
							<?php endif; ?>
							<?php endfor; ?>
						</div>
					</div>

					<?php if ($live): ?>
					<div class="col-12 col-12-md col-12-sm cluster__excerpt cluster__excerpt--live">
						<?php view::file('article/excerpt/live', ['item' => $live]); ?>
					</div>
					<?php endif; ?>

					<?php for($i = 0; $i < 6; $i++): ?>
					<?php if ($article = array_shift($today)): ?>
					<div class="col-4 col-4-md col-4-sm">
						<?php view::file('article/excerpt/default', ['item' => $article, 'modifier' => 'home']); ?>
					</div>
					<?php endif; ?>
					<?php endfor; ?>
				</div>
				<div class="">
					<?php view::file('newsletter/banner', ['type' => 'default']) ?>
				</div>

				<?php view::file('home/dossiers', [
					'items' => $dossiers,
					'location' => 3,
					'wrap' => ['<div class="collection-above-opinion">', '</div>']
				]) ?>

				<?php view::banner('home_1_mobile') ?>

				<div class="col cluster--section-opinion">
					<div class="cluster__header">
						<a href="<?php view::route('opinion') ?>" title="<?php view::lang('more opinion'); ?>"
							data-track="cluster-header <?php view::lang('opinion'); ?>">
							<h2 class="cluster__label header--style-3"><?php view::lang('opinion'); ?></h2>
						</a>
					</div>
					<div class="row-flex">
						<?php foreach ($opinion as $index => $article): ?>
						<div class="col-4 col-6-md col-4-sm <?php view::attr($index == 5 ? 'hidden-xs' : '') ?>">
							<?php view::file('article/excerpt/default', [
								'item' => $article,
								'context'=>'opinion',
								'teaser' => false,
								'nolabel' => ['opinion', 'keyword']
							]); ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>

				<hr class="mb-4" style="border-width: 0px;" />
			</section>

			<?php $carouselSlides = fetch::home_carousel(); ?>
			<?php if(!empty($carouselSlides)): ?>
			<!-- Mobile Carousel -->
			<section class="cluster cluster--section-carousel sidebar">
				<div class="row-flex">
					<div class="col-12">
						<?php view::file('home/new_carousel', [
								'slides' => $carouselSlides,
								'class' => 'mobile-carousel'
							]); ?>
					</div>
				</div>
			</section>
			<?php endif; ?>

			<?php view::file('home/dossiers', [
				'items' => $dossiers,
				'location' => 2,
				'wrap' => ['', '']
			]); ?>

			<?php /* Mobile Sponsored & Advertorial */ ?>
			<?php if (isset($sponsored[0]) && !empty($sponsored)): ?>
			<div class="hidden-sm-up sponsored--mobile-banner">
				<div class="plus plus--banner-header">
					<sup>MT<sup class="plus__sub">+</sup></sup>
				</div>
				<?php view::file('article/excerpt/default', ['item' => $sponsored[0]]); ?>
			</div>
			<?php  elseif ( isset($advertorials[0]) ): ?>
			<div class="hidden-sm-up">
				<div class="plus plus--banner-header">
					<a href="<?php view::url('base'); ?>plus/<?php view::text($advertorials[0]['campaign']['slug']); ?>"
						title="<?php view::text($advertorials[0]['campaign']['title']); ?>">MT<sup class="plus__sub">+</sup></a>
				</div>
				<?php view::file('advertorial/excerpt/banner', ['item' => $advertorials[0]]) ?>
			</div>
			<?php endif;  ?>
		</div>


		<div class="col-auto">
			<aside class="sidebar hidden-sm-down">

				<?php view::banner('home_1') ?>

				<?php view::banner('home_2') ?>

				<div class="sidebar__sticky">
					<section class="sidebar__section">
						<div class="sidebar__section__header">
							<h3 class="header--style-3">Just in</h3>
						</div>
						<?php view::recent(); ?>
					</section>

					<?php if(!empty($carouselSlides)): ?>
					<?php view::file('home/new_carousel', [
							'slides' => $carouselSlides,
							'class' => 'desktop-carousel'
						]); ?>
					<?php endif; ?>

					<hr class="mb-2" />
					<section class="sidebar__section">
						<div class="social-follow">
							<span class="social-follow__header">Follow us:</span>
							<div class="social-follow__icons">
								<a href="https://www.facebook.com/MoscowTimes" class="social__icon social__icon--facebook"
									title="<?php view::lang('Facebook'); ?>" target="_blank"><i class="fa fa-facebook"></i></a>
								<a href="https://twitter.com/MoscowTimes" class="social__icon social__icon--x-twitter"
									title="<?php view::lang('Twitter'); ?>" target="_blank"><i class="fa fa-brands fa-x-twitter"></i></a>
								<a href="https://t.me/+fmbCxJOTTPMyZjQy" class="social__icon social__icon--telegram"
									title="<?php view::lang('Telegram'); ?>" target="_blank"><i class="fa fa-paper-plane"></i></a>
								<a href="<?php view::url('base'); ?>rss/news" class="social__icon social__icon--rss"
									title="<?php view::lang('RSS feed'); ?>" target="_blank"><i class="fa fa-rss"></i></a>
								<a href="<?php view::url('base')?>newsletters" class="social__icon social__icon--newsletter"
									title="<?php view::lang('Newsletter'); ?>"><i class="fa fa-envelope"></i></a>
								<a href="https://www.youtube.com/channel/UCRNPdAfK5Mp8ORtjUt3Q8UA"
									class="social__icon social__icon--youtube" title="<?php view::lang('YouTube'); ?>"><i
										class="fa fa-youtube"></i></a>
								<?php /*
								<a data-flip-widget="ico"
									href="https://flipboard.com/@MoscowTimes?utm_campaign=tools&utm_medium=follow&action=follow"
									title="<?php view::lang('Flipboard'); ?>" class="social__icon social__icon--flipboard">
								<img src="<?php view::url('static'); ?>img/flipboard_mrrw.png" alt="Flipboard" />
								</a>
								<script src="https://cdn.flipboard.com/web/buttons/js/flbuttons.min.js" type="text/javascript">
								*/ ?>
								</script>
							</div>
						</div>
					</section>
					<?php /*
						$day =  date('w');
						$day_conditional = ($day == 0 || $day == 6);
						$telegram = fetch::telegram_russian();
						if (isset($telegram) && !empty($telegram) && !$day_conditional):
					?>
					<hr class="mb-2" />
					<section class="sidebar__section">
						<div class="sidebar__section__header">
							<h3 class="header--style-3">Live Feed - MT Russian Service</h3>
						</div>
						<ul class="listed-articles">
							<?php foreach(array_reverse(array_slice($telegram, -5, 5)) as $item): ?>
							<li class="listed-articles__item">
								<a style="display: block;" class="live-telegram-feed"
									data-track="live-telegram-feed <?php view::text($item->title); ?>"
									href="https://t.me/moscowtimes_ru/<?php view::text($item->telegram_post_id); ?>">
									<time class="article-excerpt-tiny__time"
										datetime="<?php view::text(date('c', strtotime($item->created))); ?>" y-use="Timeago">
										<?php view::date(strtotime($item->created)); ?>
									</time>
									<h5 class="article-excerpt-tiny__headline"><?php view::text($item->title); ?></h5>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					</section>
					<?php endif; */ ?>
					<!-- 300x600 Opinion Unit -->
					<?php view::banner('home_4'); ?>
				</div>
			</aside>
		</div>
	</div>
</div>



<?php view::banner('home_3') ?>
<?php view::banner('home_3_mobile') ?>

<?php /* Comment out opinion
<div class="container">
	<section class="cluster cluster--section-opinion">
		<div class="row-flex">
			<div class="col">
				<div class="cluster__header">
					<a href="<?php view::route('opinion') ?>" title="<?php view::lang('more opinion'); ?>"
data-track="cluster-header <?php view::lang('opinion'); ?>">
<h2 class="cluster__label header--style-3"><?php view::lang('opinion'); ?></h2>
</a>
</div>
<div class="row-flex">
	<?php foreach ($opinion as $index => $article): ?>
	<div class="col-4 col-6-md col-4-sm <?php view::attr($index == 5 ? 'hidden-xs' : '') ?>">
		<?php view::file('article/excerpt/default', [
								'item' => $article,
								'context'=>'opinion',
								'teaser' => false,
								'nolabel' => ['opinion', 'keyword']
							]); ?>
	</div>
	<?php endforeach; ?>
</div>
</div>
<div class="col-auto hidden-sm-down">
	<aside class="sidebar" style="">
		<?php view::banner('home_4'); ?>
	</aside>
</div>
</div>
<div class="cluster__more">
	<a href="<?php view::route('opinion') ?>" title="<?php view::lang('more opinion'); ?>"
		data-track="cluster-more <?php view::lang('opinion'); ?>"><?php view::lang('more opinion'); ?></a>
</div>
</section>
</div>
*/ ?>

<section class="cluster cluster--section-meanwhile mb-4 pt-4">
	<div class="container">
		<div class="cluster__header">
			<h2 class="cluster__label header--style-3">
				<a href="<?php view::route('gallery') ?>"
					data-track="cluster-header <?php view::lang('Photos'); ?>"><?php view::lang('Photos'); ?></a> and
				<a href="<?php view::route('video') ?>"
					data-track="cluster-header <?php view::lang('videos'); ?>"><?php view::lang('Videos'); ?></a>
			</h2>
		</div>
		<div class="row-flex">
			<?php foreach (array_slice($photos_videos, 0, 4)  as $index => $article): ?>
			<div class="col-3 col-3-md col-6-sm <?php view::attr($index == 3 ? 'hidden-xs' : '') ?>">
				<?php view::file('article/excerpt/default', [
						'item' => $article,
						'context'=>'meanwhile',
						'teaser' => false,
						'duration' => false,
					]); ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /*
<section class="cluster cluster--section-testimonials">
	<div class="container">
		<?php view::file('home/carousel-ambassadors', [
		'ambassadors' => $ambassadors
	]); ?>
</div>
</section>
*/ ?>


<div class="container">
	<section class="cluster cluster--indepth ">
		<div class="row-flex">
			<div class="col">
				<div class="cluster__header">
					<a href="<?php view::route('indepth') ?>" title="<?php view::lang('more in depth'); ?>"
						data-track="cluster-header <?php view::lang('In depth'); ?>">
						<h2 class="cluster__label header--style-3"><?php view::lang('In depth'); ?></h2>
					</a>
				</div>
				<div class="row-flex">
					<?php foreach ($indepth as $index => $article): ?>
					<div class="<?php view::attr($index == 0 ? 'col-8 col-12-md col-8-sm' : 'col-4 col-6-md col-4-sm'); ?>">
						<?php view::file('article/excerpt/default', [
								'item' => $article,
								'modifier' => $index == 0 ? 'primary' : '',
								'image' => $index == 0 ?  'article_960' :  'article_640',
								'nolabel' => ['indepth']
							]); ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-auto hidden-sm-down">
				<aside class="sidebar">
					<section class="sidebar__section">
						<div class="sidebar__section__header">
							<h3 class="header--style-3">Most read</h3>
						</div>
						<?php view::mostread(); ?>
					</section>

					<?php /* Desktop Advertorial & Sponsored */ ?>
					<?php if (isset($sponsored[0]) && !empty($sponsored)): ?>
					<section class="sidebar__section">
						<div class="plus plus--banner-header">
							<sup>MT<sup class="plus__sub">+</sup></sup>
						</div>
						<?php view::file('article/excerpt/default', ['item' => $sponsored[0]]); ?>
					</section>
					<?php  elseif(isset($advertorials[0])): ?>
					<div class="plus">
						<a href="<?php view::url('base'); ?>plus/<?php view::text($advertorials[0]['campaign']['slug']); ?>"
							title="<?php view::text($advertorials[0]['campaign']['title']); ?>">MT<sup class="plus__sub">+</sup></a>
					</div>
					<?php view::file('advertorial/excerpt/banner', ['item' => $advertorials[0]]) ?>
					<?php endif; ?>

				</aside>
			</div>
		</div>
		<div class="cluster__more">
			<a href="<?php view::route('indepth') ?>" title="<?php view::lang('read more'); ?>"
				data-track="cluster-more <?php view::lang('In depth'); ?>"><?php view::lang('More in depth'); ?></a>
		</div>
		<hr class="mb-4" style="border-width: 1px;" />
	</section>
</div>


<?php view::banner('home_5') ?>
<?php view::banner('home_5_mobile') ?>

<div class="container">
	<section class="cluster cluster--climate ">
		<div class="row-flex">
			<div class="col">
				<div class="cluster__header">
					<a href="<?php view::route('climate') ?>" title="<?php view::lang('more climate'); ?>"
						data-track="cluster-header <?php view::lang('Diaspora'); ?>">
						<h2 class="cluster__label header--style-3"><?php view::lang('climate'); ?></h2>
					</a>
				</div>
				<div class="row-flex">
					<?php foreach ($climate as $index => $article): ?>
					<div class="col-3 col-6-md col-3-sm <?php view::attr($index == 3 ? 'hidden-xs' : '') ?>">
						<?php view::file('article/excerpt/default', [
								'item' => $article,
								'context'=>'climate',
								'teaser' => true,
							]); ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="cluster__more">
			<a href="<?php view::route('climate') ?>" title="<?php view::lang('More climate'); ?>"
				data-track="cluster-more <?php view::lang('climate'); ?>"><?php view::lang('More climate'); ?></a>
		</div>
		<hr class="mb-4" style="border-width: 1px;" />
	</section>
</div>

<div class="container">
	<section class="cluster cluster--business ">
		<div class="cluster__header">
			<a href="<?php view::route('business') ?>" title="<?php view::lang('more business'); ?>"
				data-track="cluster-header <?php view::lang('Business'); ?>">
				<h2 class="cluster__label header--style-3"><?php view::lang('Business'); ?></h2>
			</a>
		</div>
		<div class="row-flex">
			<div class="col">
				<div class="row-flex">
					<?php foreach ($business as $article): ?>
					<div class="col-4 col-6-md col-4-sm">
						<?php view::file('article/excerpt/default', [
							'item' => $article,
							'context'=>'business',
							'teaser' => true
						]); ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php /*
			<div class="col-auto col-12-sm">
				<aside class="sidebar">
					<?php view::file('newsletter/banner', ['type' => 'bell']) ?>
			</aside>
		</div>
		*/ ?>
</div>
<div class="cluster__more">
	<a href="<?php view::route('business') ?>" title="<?php view::lang('more business'); ?>"
		data-track="cluster-more <?php view::lang('Business'); ?>"><?php view::lang('More business'); ?></a>
</div>
<hr class="mb-4" style="border-width: 1px;" />
</section>
</div>

<div class="container">
	<section class="cluster cluster--section-city">
		<div class="row-flex">
			<div class="col col-12-sm">
				<div class="cluster__header">
					<a href="<?php view::route('city') ?>" title="<?php view::lang('More Arts and Life'); ?>"
						data-track="cluster-header <?php view::lang('Arts and Life'); ?>">
						<h2 class="cluster__label header--style-3"><?php view::lang('arts and life'); ?></h2>
					</a>
				</div>
				<div class="row-flex">
					<?php foreach ($city as $index => $article): ?>
					<div class="col-3 col-6-md col-3-sm <?php view::attr($index == 3 ? 'hidden-xs' : '') ?>">
						<?php view::file('article/excerpt/default', [
								'item' => $article,
								'modifier'=> 'arts-and-life'
							]); ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="col-auto col-12-sm">

				<?php /* Mobile advertorial bottom */ ?>
				<?php if (isset($advertorials[1])): ?>
				<aside class="sidebar hidden-sm">
					<section class="sidebar__section">
						<div class="plus plus--banner-header">
							<a href="<?php view::url('base'); ?>plus/<?php view::text($advertorials[1]['campaign']['slug']); ?>"
								title="<?php view::text($advertorials[1]['campaign']['title']); ?>">MT<sup class="plus__sub">+</sup></a>
						</div>
						<?php view::file('advertorial/excerpt/banner', ['item' => $advertorials[1]]) ?>
					</section>
				</aside>
				<?php endif; ?>

			</div>
		</div>
		<div class="cluster__more">
			<a href="<?php view::route('city') ?>" title="<?php view::lang('More Arts and Life'); ?>"
				data-track="cluster-more <?php view::lang('Arts and Life'); ?>"><?php view::lang('More Arts and Life'); ?></a>
		</div>
		<hr class="mb-4" style="border-width: 1px;" />
	</section>
</div>

<div class="container">
	<section class="cluster cluster--diaspora ">
		<div class="row-flex">
			<div class="col">
				<div class="cluster__header">
					<a href="<?php view::route('diaspora') ?>" title="<?php view::lang('more diaspora'); ?>"
						data-track="cluster-header <?php view::lang('Diaspora'); ?>">
						<h2 class="cluster__label header--style-3"><?php view::lang('the new diaspora'); ?></h2>
					</a>
				</div>
				<div class="row-flex">
					<?php foreach ($diaspora as $index => $article): ?>
					<div class="col-3 col-6-md col-3-sm <?php view::attr($index == 3 ? 'hidden-xs' : '') ?>">
						<?php view::file('article/excerpt/default', [
								'item' => $article,
								'context'=>'diaspora',
								'teaser' => true,
								'nolabel' => ['indepth']
							]); ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="cluster__more">
			<a href="<?php view::route('diaspora') ?>" title="<?php view::lang('More the new diaspora'); ?>"
				data-track="cluster-more <?php view::lang('Diaspora'); ?>"><?php view::lang('More the new diaspora'); ?></a>
		</div>
	</section>
</div>

<script>
if (typeof window.freestar === 'object') {
	freestar.config.disabledProducts = {
		sideWall: true,
		video: true,
		stickyFooter: true
	};
}
</script>

<?php view::end(); ?>