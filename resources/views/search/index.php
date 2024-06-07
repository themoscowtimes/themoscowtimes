<?php
view::extend('template/default');
// view::block('seo', fetch::seo('archive'));
view::block('body.class', 'article-index');
view::asset('js', 'https://cdn.jsdelivr.net/npm/flatpickr');
view::asset('js', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/nl.js');
view::asset('css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
?>


<?php view::start('billboard') ?>
<?php view::banner('section_top') ?>
<?php view::end() ?>


<?php view::start('main') ?>

<div class="container--full" y-use="search.Advanced" data-articles="<?php view::route('apisearch') ?>"
	data-authors="<?php view::route('apisearchauthors') ?>">
	<div class="search-header">
		<picture class="search-header__media">
			<?php if ($image): ?>
			<?php foreach ([
					'1024' => '1920',
					'960' => '1360',
					'640' => '960',
				] as $breakpoint => $preset): ?>
			<source media="(min-width: <?php view::attr($breakpoint) ?>px)" srcset="<?php view::src($image, $preset) ?>">
			<?php endforeach; ?>
			<img class="search-header__media__img" src="<?php view::src($image, '1360') ?>">
			<?php else: ?>
			<img class="search-header__media__img"
				src="<?php view::attr($fetch::url('static') . 'img/archive/header.jpg')?>" />
			<?php endif; ?>
		</picture>

		<div class="search-header__content">
			<div class="search-header__text mb-1">
				<div class="container">
					<div class="search-header__textwrap search-header__textwrap--maximized">
						<p class="search-header__textwrap__subtitle">
							30 years of independent journalism
						</p>
					</div>
					<div class="search-header__textwrap">
						<h1 class="search-header__textwrap__title">Search the archive</h1>
					</div>
				</div>
			</div>
			<div class="search-header__search">
				<div class="container">
					<div class="search-box">
						<div class="search-box__element mb-2">
							<div class=" " y-name="input-wrapper">
								<label class="form__label">Search for&nbsp;&nbsp;</label>
								<div class="search-box__search">
									<input type="text" y-name="query" value="<?php view::attr($query) ?>" placeholder=""
										class="form-control search-box__search__input">
									<button y-name="submit" value="" class="search-box__search__submit clickable">
										<i class="fa fa-search "></i>
									</button>
								</div>
							</div>
						</div>
						<div class="search-box__refine">
							<div class="search-box__refine__filters">
								<div class="search-select clickable" y-name="select" data-name="date">
									<div class="search-select__label" y-name="label rangeLabel">Date range</div>
									<div class="search-select__container" style="display:none;" y-name="options">
										<div class="search-select__options">
											<div class="search-select__option clickable" y-name="option" data-option="">Any</div>
											<div class="search-select__option clickable" y-name="option" data-option="week">Past week</div>
											<div class="search-select__option clickable" y-name="option" data-option="month">Past month</div>
											<div class="search-select__option clickable" y-name="option" data-option="year">Past year</div>
											<div class="search-select__option clickable" y-name="option" data-option="range">Date range</div>
										</div>
										<div class="search-select__dates" y-name="range" style="display:none">
											<div class="mb-2">
												<label class="search-select__dates__label">From&nbsp;&nbsp;</label>
												<input type="date" value="<?php view::attr($from) ?>"
													class="search-select__dates__date form-control" y-name="from">
											</div>
											<div class="">
												<label class="search-select__dates__label">To&nbsp;&nbsp;</label>
												<input type="date" value="<?php view::attr($to) ?>"
													class="search-select__dates__date form-control" y-name="to">
											</div>
										</div>
									</div>
								</div>

								<div class="search-select clickable" y-name="select" data-name="section">
									<div class="search-select__label" y-name="label">Section</div>
									<div class="search-select__container" style="display:none;" y-name="options">
										<div class="search-select__options">
											<div class="search-select__option clickable" y-name="option" data-option="">Any</div>
											<div class="search-select__option clickable" y-name="option" data-option="news">News</div>
											<div class="search-select__option clickable" y-name="option" data-option="business">Business</div>
											<div class="search-select__option clickable" y-name="option" data-option="opinion">Opinion</div>
											<div class="search-select__option clickable" y-name="option" data-option="city">Arts and Life
											</div>
											<div class="search-select__option clickable" y-name="option" data-option="video">Videos</div>
											<div class="search-select__option clickable" y-name="option" data-option="gallery">Galleries</div>
											<div class="search-select__option clickable" y-name="option" data-option="podcast">Podcasts</div>
										</div>
									</div>
								</div>
							</div>

							<div class="search-box__refine__sort">
								<div class="search-select search-select--right clickable" y-name="select" data-name="order">
									<div class="search-select__label" y-name="label">Order</div>
									<div class="search-select__container" style="display:none;" y-name="options">
										<div class="search-select__options">
											<div class="search-select__option clickable" y-name="option" data-option="relevance">Relevance
											</div>
											<div class="search-select__option clickable" y-name="option" data-option="date">New to old</div>
											<div class="search-select__option clickable" y-name="option" data-option="date_reverse">Old to new
											</div>
										</div>
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
		<div class="search-meta mt-2" y-name="authors" style="display:none">
			<div class="search-meta__authors mb-3">
				<div y-name="authors-current">
					<div class="search-meta__authors__title">Found author(s) from 2016 to present:</div>
					<div class="search-meta__authors__description">Click on a name to see all the author’s articles</div>
					<div class="search-meta__authors__mount mt-1 mb-3" y-name="mount"></div>
				</div>

				<div y-name="authors-archive">
					<div class="search-meta__authors__title">Found author(s) in the archive from 1992 to 2016: </div>
					<div class="search-meta__authors__description">
						There have been a lot of contributors throughout the years. We have tried to bundle all the articles of the same contributor for you.
						<br /> Click on a name to see all the author’s articles. If we made a mistake, let us know!
					</div>
					<div class="search-meta__authors__mount mt-1" y-name="mount"></div>
				</div>



			</div>

			<div class="search-meta__clear clickable" y-name="clear" style="display:none;">
				Clear<span class="hidden-sm-down"> search</span><i class="search-meta__clear__icon fa fa-times-circle"></i>
			</div>

		</div>
		<div y-name="loading" class="loading" style="display:none">
			<div class="loading__progress"></div>
		</div>
		<div class="row-flex" y-name="articles" style="display:none">
			<div class="col">
				<div class="cluster mb-3">
					<div y-name="label" class="cluster__label mb-2 mt-3">Found <span y-name="articles-amount"></span> articles</div>
					<div y-name="mount" class=""></div>
					<div class="clickable align-center" style="margin-top: 24px;" y-name="more" style="display:none">
						<div class="button">Show more results</div>
					</div>
				</div>


			</div>


			<div class="col-auto">
				<aside class="sidebar hidden-sm-down">
					<section class="sidebar__section" y-name="banner">
						<div class="banner" style="">
							<div class="banner-inner">

							</div>
						</div>
					</section>
				</aside>
			</div>
		</div>
	</div>

	<div y-name="archive">
		<div class="container">
			<div class="mb-3">
				<p><strong>The Moscow Times</strong> has been Russia’s leading independent English-language media outlet
					since 1992, publishing daily stories about politics, society, economy and culture. From the
					privatizations of the 1990s and Putin’s rise to power to ballet performances and the invasion of
					Ukraine, our archive is an essential instrument to understand and explore every aspect of Russia’s
					post-Soviet history.</p>
			</div>
		</div>
	</div>
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