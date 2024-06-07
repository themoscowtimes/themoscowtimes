<?php
view::extend('template/default');
view::block('body.class', 'article-index');
view::asset('js', 'https://cdn.jsdelivr.net/npm/flatpickr');
view::asset('js', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/nl.js');
view::asset('css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
?>


<?php view::start('billboard') ?>
<?php view::banner('section_top') ?>
<?php view::end() ?>


<?php view::start('main') ?>
<div class="container--full">
	<div class="archive-header">
		<picture class="archive-header__media">
			<img class="archive-header__media__img"
				src="<?php view::attr($image ? fetch::src($image, '1360') : fetch::url('static') . 'img/archive/header.jpg')?>" />
		</picture>

		<div class="archive-header__content">
			<div class="archive-header__text mb-1">
				<div class="container">
					<div class="archive-header__textwrap archive-header__textwrap--maximized">
						<p class="archive-header__textwrap__subtitle">
							30 years of independent journalism
						</p>
					</div>
					<div class="archive-header__textwrap">
						<h1 class="archive-header__textwrap__title">Search the archive</h1>
					</div>
				</div>
			</div>
			<div class="archive-header__search">
				<div class="container">
					<div class="archive-search">
						<div class="row-fluid" y-use="search.Archive" data-url="<?php view::route('search') ?>">
							<div class="col-6 col-12-sm archive-search__element ">
								<div class="required " y-name="input-wrapper">
									<label class="form__label">Search for&nbsp;&nbsp;</label>
									<input type="text" name="" y-name="query" value="" placeholder="" class="form-control" required="">
								</div>
							</div>
							<div class="col-2 col-4-sm col-6-xs archive-search__element ">
								<div class="required " y-name="input-wrapper">
									<label class="form__label">From&nbsp;&nbsp;</label>
									<input type="date" name="" y-name="from" value="1992-03-06" class="form-control" required="">
								</div>
							</div>
							<div class="col-2 col-4-sm col-6-xs archive-search__element ">
								<div class="required " y-name="input-wrapper">
									<label class="form__label">To&nbsp;&nbsp;</label>
									<input type="date" name="" y-name="to" value="<?php view::attr(date('Y-m-d')) ?>" class="form-control"
										required="">
								</div>
							</div>
							<div class="col-2 col-4-sm col-12-xs archive-search__element">
								<div class="">
									<a href="#" y-name="submit" class="archive-search__button button button--secondary">Search</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row-flex">
		<div class="col">
			<section class="cluster">
				<div>
					<p>The Moscow Times has been Russia’s leading independent English-language media outlet since 1992, publishing
						daily stories about politics, society, economy and culture. From the privatizations of the 1990s and Putin’s
						rise to power to ballet performances and the invasion of Ukraine, our archive is an essential instrument to
						understand and explore every aspect of Russia’s post-Soviet history.</p>
				</div>
				<div class="row-flex">
					<?php if (isset($articles[0])): ?>
					<?php $article = array_shift($articles) ?>
					<div class="col-12 col-12-md col-12-sm cluster__excerpt cluster__excerpt--lead">
						<?php view::file('article/excerpt/lead', ['item' => $article, 'modifier' => 'primary', 'archive' => $article->archive]); ?>
					</div>
					<?php endif; ?>

					<?php foreach ($articles as $article): ?>
					<div class="col-4 col-4-md col-4-sm">
						<?php view::file('article/excerpt/default', ['item' => $article, 'modifier' => '', 'archive' => $article->archive]); ?>
					</div>
					<?php endforeach; ?>
				</div>
				<hr class="mb-4" />
			</section>
		</div>

		<div class="col-auto">
			<aside class="sidebar hidden-sm-down" style="">
				<?php /** Create custom banners */ ?>
				<?php /* view::banner('home_1') */ ?>
				<?php /* view::banner('home_2') */ ?>
			</aside>
		</div>
	</div>
</div>

<div class="container">
	<section class="cluster cluster--business ">
		<div class="row-flex">
			<div class="col">
				<div class="cluster__header">
					<a href="<?php view::route('issues') ?>" title="<?php view::lang('See all issues'); ?>"
						data-track="cluster-header <?php view::lang('Print editions'); ?>">
						<h2 class="cluster__label header--style-3"><?php view::lang('Print editions'); ?></h2>
					</a>
				</div>
				<div class="row-flex">
					<?php foreach ($issues as $issue): ?>
					<div class="col">
						<?php view::file('issue/excerpt/extended', ['item' => $issue]) ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="cluster__more mt-2">
			<a href="<?php view::route('issues') ?>"
				title="<?php view::lang('See all issues'); ?>"><?php view::lang('See all issues'); ?></a>
		</div>
		<hr class="mb-4">
	</section>
</div>


<div class="container">
	<section class="cluster ">
		<div class="row-flex">
			<div class="col">
				<div class="row-flex">
					<?php foreach([10, 20, 30] as $years): ?>
					<?php if (isset($history[$years])): ?>
					<div class="col-4">
						<div class="cluster__header">
							<h2 class="cluster__label header--style-3">This week <?php view::text($years) ?> years ago</h2>
						</div>
						<?php view::file('article/excerpt/default', ['item' => $history[$years], 'archive' => true]) ?>
					</div>
					<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="cluster__more">
			<a href="<?php view::route('search') ?>"
				title="<?php view::lang('Search the archive'); ?>"><?php view::lang('Search the archive'); ?></a>
		</div>
		<hr class="mb-4">
	</section>
</div>
<script>
if (typeof window.freestar === 'object') {
	freestar.config.disabledProducts = {
		stickyFooter: true,
		video: true,
		revolvingRail: true,
		pushdown: true,
		dynamicAds: true,
		superflex: true,
		slidingUnit: true,
		sideWall: true,
		pageGrabber: true,
		googleInterstitial: true,
	};
}
</script>
<?php view::end() ?>