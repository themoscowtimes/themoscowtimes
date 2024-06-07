<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('contribute')) ?>

<?php view::block('body.class', 'content-item--full-header') ?>

<?php view::start('main') ?>

<section class="donate-header">
	<picture class="donate-header__media">
		<source media="(min-width: 1360px)" srcset="<?php view::url('static'); ?>img/contribute/hero_2048.jpg">
		<source media="(min-width: 1024px)" srcset="<?php view::url('static'); ?>img/contribute/hero_1360.jpg">
		<source media="(min-width: 768px)" srcset="<?php view::url('static'); ?>img/contribute/hero_768.jpg">
		<source media="(min-width: 480px)" srcset="<?php view::url('static'); ?>img/contribute/hero_768.jpg">
		<img class="donate-header__media__img" src="<?php view::url('static'); ?>img/contribute/hero_1360.jpg">
	</picture>

	<div class="donate-header__wrap">
		<header class="donate-header__content">
			<div class="container container--large">
				<div class="donate-header__title">
					<h1 class="donate-header__title__text">Together we keep independent journalism alive</h1>
				</div>
				<div class="donate-header__title donate-header__title--maximized">
					<p class="donate-header__title__paragraph">Since 1992, The Moscow Times has been Russia’s leading independent English-language media outlet. To keep our news rolling, we need your support.</p>
				</div>
				<div class="donate-header__buttons">
					<a href="#single-cta" class="donate-header__button clickable">Donate now</a>
					<a href="#monthly" class="donate-header__button clickable">Become a recurring contributor</a>
				</div>
			</div>
		</header>
	</div>
</section>
<section class="donate-text mt-3">
	<div class="container container--medium ">
		<a id="single" class="donate-text__anchor"></a>
		<header class="donate-text__header ">
			<h1>The Pressure is Real</h1>
		</header>
		<div class="donate-text__content">
			<p>
				Russia is a place where a huge number of critically important stories are playing out in real time — and with a core editorial team of eight, there is no shortage of work to be done.
			</p>
			<p>
				The country is warming twice as fast as the rest of the world, making it ground zero for the unfolding climate crisis. Following a controversial vote, President Vladimir Putin now has the power to remain president until 2036. The city of Moscow is pioneering facial recognition surveillance systems that raise new questions of citizens’ rights to digital privacy.
			</p>
			<p>
				At the same time, the challenges facing independent journalists here have never been greater, including politically motivated arrests, financial pressure, self-censorship and more.
			</p>
		</div>
		<div class="donate-text__content">
			<img width="100%" src="https://static.themoscowtimes.com/image/article_1360/ab/C4BFE2D8-16EF-4804-BD17-21593D6A44E8.jpeg" alt="Independent journalism is dead" />
		</div>
		<div class="donate-text__content">
			<p>
				Just last year, top journalists at the country’s leading independent business newspaper Vedomosti walked out after the installment of a new editor who banned them from publishing critical coverage of Putin and Gazprom. The pressure on independent journalists is real.
			</p>
		</div>
		<div class="donate-text__content">
			<a id="single-cta" class="donate-text__anchor"></a>
			<h2>Donate Now</h2>
			<p class="donate-text__content__highlight">
				With as little as $50 a year in support from each contributor, we could do more investigative journalism such as our reporting on the coronavirus and bringing you the stories of our Russian colleagues who are threatened and bullied on a daily basis, for telling the truth.
			</p>
		</div>
	</div>
</section>

<a id="single-form" class="donate-text__anchor"></a>
<?php view::file('contribute/form', [
	'period' => 'once',
	'amounts' =>  ['25' => '$25', '50' => '$50', '100' => '$100' ,'250' => '$250' ,'other' => 'Other'],
	'amount' => 50
]) ?>

<section class="donate-text mt-3">
	<div class="container container--medium ">
		<a id="monthly" class="donate-text__anchor"></a>
		<header class="donate-text__header ">
			<h1>Is there hope?</h1>
		</header>
		<div class="donate-text__content">
			<p>There is hope.<br/>Those journalists from Vedomosti have since gone on to form a new media startup, <a href="https://www.vtimes.io/" target="_blank" title="VTimes">VTimes</a>, which is a partner to The Moscow Times.</p>
			<p><strong>And there is you.</strong> Since 2020 the number of contributors to The Moscow Times has grown significantly. Right now over 1,000 individuals are supporting our cause and have contributed over $60,000. Thank you!</p>
		</div>
		<div class="donate-text__content">
			<img width="100%" src="<?php view::url('static'); ?>img/contribute/is-there-hope.jpg" alt="Is there hope?" />
		</div>

		<div class="donate-text__content">
			<p> Amid the coronavirus pandemic, The Moscow Times has been first with the big stories since day one. Our exclusives and <a href="https://themoscowtimes.us10.list-manage.com/track/click?u=239926d40266233686ee429be&id=cda47d972d&e=3d10c387d3" title="CORONAVIRUS" target="_blank" >on-the-ground reporting</a> are read and shared by many high-profile journalists.</p>
			<p>
				You value us for our independent news coverage, but also for our stories and podcasts on Russian daily life and language. You also appreciate our WEEKEND KITCHEN, our contemporary approach on  traditional Russian recipes.
			</p>
			<p>
				Have you tried <a href="https://www.themoscowtimes.com/2020/12/26/russias-national-treasure-cabbage-soup-a72490" target="_blank" title="cabbage soup">this cabbage soup</a> already?
			</p>
		</div>
		<div class="donate-text__content">
			<a id="monthly-cta" class="donate-text__anchor"></a>
			<h2>Become a monthly contributor</h2>
			<p class="donate-text__content__highlight">
				A monthly contribution gives us most security throughout the year.<br />
				The average monthly contribution in 2020 was $9 a month.
			</p>
		</div>
	</div>
</section>

<a id="monthly-form" class="donate-text__anchor"></a>
<?php view::file('contribute/form', [
	'period' => 'monthly',
	'amounts' =>  ['6' => '$6', '10' => '$10', '20' => '$20' ,'other' => 'Other'],
	'amount' => 10
]) ?>


<section class="donate-text mt-3">
	<div class="container container--medium ">
		<a id="annual" class="donate-text__anchor"></a>
		<header class="donate-text__header ">
			<h1>2021 and onwards</h1>
		</header>
		<div class="donate-text__content">
			<img width="100%" src="<?php view::url('static'); ?>img/contribute/2021-and-onwards.jpg" alt="2021 and onwards " />
		</div>
		<div class="donate-text__content">
			<p>In 2020, The Moscow Times excelled in reporting on the Covid-19 crisis in Russia with many groundbreaking stories.</p>
			<p>2021 started off with the Navalny saga — and with key elections in the fall, this promises to be another eventful year for Russia. With a dedicated multinational team of reporters and editors, we are ready to bring you the most critical Russia news as it happens.</p>
		</div>
		<div class="donate-text__content">
			<a id="annual-cta" class="donate-text__anchor"></a>
			<h2>Become an annual contributor</h2>
			<p class="donate-text__content__highlight">
				With more support, we could produce better and more frequent multimedia projects that show the fascinating and vibrant diversity of modern Russia, its culture and curiosities.
			</p>
			<p class="donate-text__content__highlight">
				In 2020, the average annual contribution was $80. A yearly contribution will be automatically renewed every year. With an annual contribution of $100 per donor we could send our journalists to Siberia and the Arctic to report from the frontlines of climate change.
			</p>
		</div>
	</div>
</section>

<a id="annual-form" class="donate-text__anchor"></a>
<?php view::file('contribute/form', [
	'period' => 'annual',
	'amounts' =>  ['50' => '$50', '100' => '$100', '250' => '$250' ,'500' => '$500' ,'other' => 'Other'],
	'amount' => 100
]) ?>


<section class="donate-text mt-3">
	<div class="container container--medium ">
		<a id="annual" class="donate-text__anchor"></a>
		<header class="donate-text__header ">
			<h2>About contributing to The Moscow Times</h2>
		</header>

		<div class="donate-text__content">
			<p>Your contribution you make is on behalf of <a href="https://stichting2oktober.org/" title="Stichting 2 Oktober" target="_blank">Stichting 2 Oktober</a>, a Dutch foundation promoting independent journalism in Russia.</p>
			<p>If you have questions about contributing, please <a href="mailto:development@themoscowtimes.com">send us an email</a>.</p>
		</div>

	</div>
</section>

<section class="donate-teasers mt-5">
	<div class="donate-teasers__teaser">
		<div class="donate-teasers__teaser__visual">
			<img class="donate-teasers__teaser__visual__img" alt="independent" src="<?php view::url('static'); ?>img/contribute/independent.jpg">
		</div>
		<div class="donate-teasers__teaser__content">
			<h2 class="donate-teasers__teaser__title">The Moscow Times<br />Independent since 1992</h2>
			<p class="donate-teasers__teaser__body">
				The Moscow Times is Russia’s leading, independent English-language media outlet. From our Moscow newsroom, we provide readers across the world with breaking news, engaging stories and balanced journalism about the largest country on Earth.<br /><br />
				We pride ourselves on adhering to the highest journalistic standards. All editorial decisions are made independently by our team of editors and reporters, a practice that has been in place since the publication was founded in 1992.
			</p>
		</div>
	</div>
	<div class="donate-teasers__teaser donate-teasers__teaser--switched ">
		<div class="donate-teasers__teaser__visual">
			<img class="donate-teasers__teaser__visual__img" alt="Development review" src="<?php view::url('static'); ?>img/contribute/development.jpg">
		</div>
		<div class="donate-teasers__teaser__content">
			<h2 class="donate-teasers__teaser__title">Development in Review  2019/2020</h2>
			<p class="donate-teasers__teaser__body">
				From June 2019 - December 2020 1,152 contributors made 3,036 contributions.<br />
				In early 2021 we reached $60,000 and the contributions are still coming. From 2021 we focus on more recurring contributors<br /><br />
				<a href="https://mailchi.mp/themoscowtimes/development2020" target="_blank" class="donate-teasers__teaser__link">Subscribe to our Development news here. </a>
			</p>
		</div>
	</div>
	<div class="donate-teasers__teaser ">
		<div class="donate-teasers__teaser__visual">
			<img class="donate-teasers__teaser__visual__img" alt="About our team" src="<?php view::url('static'); ?>img/contribute/team.jpg">
		</div>
		<div class="donate-teasers__teaser__content">
			<h2 class="donate-teasers__teaser__title">About our team</h2>
			<p class="donate-teasers__teaser__body">
				The <strong>Moscow Times newsroom</strong> operates from Moscow with a small, but dedicated team.
				While we may be small in numbers, our reporting competes with some of the best in the business.<br />
				Our core editorial team is: Michele A. Berdy, Pjotr Sauer, Evan Gershkovich, Anna Savchenko, Jake Cordell, Sasha Sukhoveeva and Samantha Berkhead
			</p>
		</div>
	</div>
</section>
<?php view::end(); ?>