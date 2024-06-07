<?php
view::extend('template/default');
// view::block('seo', fetch::seo('archive'));
view::block('body.class', 'article-index');
?>


<?php view::start('billboard') ?>
	<?php view::banner('section_top') ?>
<?php view::end() ?>


<?php view::start('main') ?>

	<div class="container--full">
		<div class="archive-header">
			<picture class="archive-header__media">
				<img class="archive-header__media__img"src="http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/image/1360/42/c057b5f4-4f21-41eb-b635-8d8c366a6ef9-6.jpg" />
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
							<div class="row-fluid">
								<div class="col-6 col-12-sm archive-search__element ">
									<div class="required " y-name="input-wrapper">
										<label class="form__label">Search for&nbsp;&nbsp;</label>
										<input type="text" name="" y-name="" value="" placeholder="" class="form-control" required="">
									</div>
								</div>
								<div class="col-2 col-4-sm col-6-xs archive-search__element ">
									<div class="required " y-name="input-wrapper">
										<label class="form__label">Frome&nbsp;&nbsp;</label>
										<input type="date" name="" y-name="lastname" value="" class="form-control" required="">
									</div>
								</div>
								<div class="col-2 col-4-sm col-6-xs archive-search__element ">
									<div class="required " y-name="input-wrapper">
										<label class="form__label">To&nbsp;&nbsp;</label>
										<input type="date" name="" y-name="lastname"  value="2015-11-22" class="form-control" required="">
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
					<div class="row-flex">
						<div class="col-12 col-12-md col-12-sm cluster__excerpt cluster__excerpt--lead">
							lead
						</div>
						<div class="col-4 col-4-md col-4-sm">
							default
						</div>
						<div class="col-4 col-4-md col-4-sm">
							default
						</div>
						<div class="col-4 col-4-md col-4-sm">
							default
						</div>
					</div>
					<hr class="mb-4" />
				</section>
			</div>

			<div class="col-auto">
				<aside class="sidebar hidden-sm-down" style="">

					<?php view::banner('home_1') ?>

					<?php view::banner('home_2') ?>
				</aside>
			</div>
		</div>
	</div>
	
	<div class="container">
		<section class="cluster cluster--business ">
			<div class="row-flex">
				<div class="col">
					<div class="cluster__header">
						<h2 class="cluster__label header--style-3">Print editions</h2>
					</div>
					<div class="row-flex">
						<div class="col-3 ">
							view::file('issue/excerpt/extended)
						</div>
						<div class="col-3 ">
							view::file('issue/excerpt/extended)
						</div>
						<div class="col-3 ">
							view::file('issue/excerpt/extended)
						</div>
						<div class="col-3 ">
							view::file('issue/excerpt/extended)
						</div>
						
						
					</div>
				</div>
			</div>
			<div class="cluster__more">
				<a href="<?php view::route('issues') ?>" title="<?php view::lang('See all issues'); ?>"><?php view::lang('See all issues'); ?></a>
			</div>
			<hr class="mb-4">
		</section>
	</div>

	<div class="container">
		<section class="cluster ">
			<div class="row-flex">
				<div class="col">
					<div class="row-flex">
						<div class="col-4">
							<div class="cluster__header">
								<h2 class="cluster__label header--style-3">Today 10 years ago</h2>
							</div>
							article/excerpt/default
						</div>
						<div class="col-4">
							<div class="cluster__header">
								<h2 class="cluster__label header--style-3">Today 10 years ago</h2>
							</div>
							article/excerpt/default
						</div>
						<div class="col-4">
							<div class="cluster__header">
								<h2 class="cluster__label header--style-3">Today 10 years ago</h2>
							</div>
							article/excerpt/default
						</div>
						
					</div>
				</div>
			</div>
			<div class="cluster__more">
				<a href="<?php view::route('home') ?>" title="<?php view::lang('Search the archive'); ?>"><?php view::lang('Search the archive'); ?></a>
			</div>
			<hr class="mb-4">
		</section>
	</div>


	<div>
		<div class="container">
			<div class="search-box">
				<div class="row-fluid">
					<div class="col-12 search-box__element mb-2">
						<div class=" " y-name="input-wrapper">
							<label class="form__label">Search for&nbsp;&nbsp;</label>
							<div class="search-box__search">
								<input type="text" name="" y-name="" value="" placeholder="" class="form-control search-box__search__input">
								<button type="submit" value="" class="search-box__search__submit">
									<i class="fa fa-search "></i>
								</button>
							</div>
						</div>
					</div>
					<div class="col-2 col-4-sm col-6-xs search__element ">
						<div class="search-select search-select--active">
							<div class="search-select__label">Date range</div>
							<div class="search-select__container">
								<div class="search-select__options">
									<div class="search-select__option">Past week</div>
									<div class="search-select__option">Past month</div>
									<div class="search-select__option">Past year</div>
									<div class="search-select__option">Date range</div>
								</div>
								<div class="search-select__dates">
									<div class="mb-2">
										<label class="search-select__dates__label">Frome&nbsp;&nbsp;</label>
										<input type="date" name="" class="search-select__dates__date form-control" required="">
									</div>
									<div class="">
										<label class="search-select__dates__label">To&nbsp;&nbsp;</label>
										<input type="date" name="" class="search-select__dates__date form-control" required="">
									</div>
									
								</div>
							</div>
						</div>
						
						
					</div>
					<div class="col-2 col-4-sm col-6-xs archive-search__element ">
						<div class="search-select search-select--active">
							<div class="search-select__label">Sections</div>
							<div class="search-select__container">
								<div class="search-select__options">
									<div class="search-select__option">News</div>
									<div class="search-select__option">Business</div>
									<div class="search-select__option">Meanwhile</div>
									<div class="search-select__option">Russian war</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-2 col-4-sm col-12-xs archive-search__element">
						<div class="search-select search-select--active">
							<div class="search-select__label">Type</div>
							<div class="search-select__container">
								<div class="search-select__options">
									<div class="search-select__option">Article</div>
									<div class="search-select__option">Gallery</div>
									<div class="search-select__option">Podcast</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-2 col-4-sm col-12-xs archive-search__element">
						sorting
					</div>
				</div>
				<div class="search-box__authors mt-2">
					<div class="search-box__authors__author">
						geklikte auteur <span class="search-box__authors__close">&times;</span>
					</div>
				</div>
				
			</div>
		</div>
	</div>


<?php view::end() ?>
