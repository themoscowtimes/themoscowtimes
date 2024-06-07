<?php view::extend('template/default'); ?>
<?php view::block('seo', fetch::seo('contribute')) ?>
<?php view::block('body.class', 'content-item--full-header') ?>
<?php view::start('main') ?>
<section class="donate-header">
	<picture class="donate-header__media">
		<source media="(min-width: 1360px)" srcset="<?php view::url('static'); ?>img/contribute/202203_hero_1360.jpg">
		<source media="(min-width: 1024px)" srcset="<?php view::url('static'); ?>img/contribute/202203_hero_1360.jpg">
		<source media="(min-width: 768px)" srcset="<?php view::url('static'); ?>img/contribute/202203_hero_768.jpg">
		<source media="(min-width: 480px)" srcset="<?php view::url('static'); ?>img/contribute/202203_hero_768.jpg">
		<img class="donate-header__media__img" src="<?php view::url('static'); ?>img/contribute/202203_hero_1360.jpg">
	</picture>
	<div class="donate-header__wrap">
		<header class="donate-header__content">
			<div class="donate-header__grid">
				<div class="donate-header__grid__form">
					<?php view::file('contribute/form_tabs') ?>
					<?php //view::file('form/form', ['form' => $form]) ?>
				</div>
				<div class="donate-header__grid__wrap">
					<div class="donate-header__title">
						<h1 class="donate-header__title__text">Together we keep independent journalism alive</h1>
					</div>
					<div class="donate-header__title donate-header__title--maximized">
						<p class="donate-header__title__paragraph">
							As the Russia-Ukraine conflict continues, sinking Russia further into isolation not seen since the Soviet era, it is more important than ever to keep independent reporting alive.
						</p>
					</div>
				</div>
			</div>
		</header>
	</div>
</section>
<section class="donate-text mt-3">
	<div class="container container--medium ">
		<header class="donate-text__header ">
			<h1>Who we are</h1>
		</header>
		<div class="donate-text__content">
			<p>
				Since 1992, The Moscow Times has been Russia’s leading independent English-language media outlet. We have been the best source of information in English about the politics, society, economy and culture in Russia for 30 years. And despite the upheavals of war and censorship, we are still working hard to bring our readers the most up-to-date and reliable news about Russia every day.
			</p>
		</div>
	</div>
</section>

<section class="donate-text">
	<div class="container container--medium ">
		<a id="monthly" class="donate-text__anchor"></a>
		<header class="donate-text__header ">
			<h1>Unprecedented challenges</h1>
		</header>
		<div class="donate-text__content">
			<p>
				Since its inception, The Moscow Times has always been a collective of Russians, Europeans, North Americans, and Asians living in Moscow. We have never been outsiders looking in. We have always been deeply involved in and committed to Russian life and society. Our teams of “European Russians” or “Russian Europeans” have been a bridge between Russia and the rest of the world.
			</p>
		</div>
		<div class="donate-text__content">
			<img width="100%" src="<?php view::url('static'); ?>img/contribute/2022_omon.jpg" alt="Unprecedented challenges" />
		</div>
	</div>
</section>

<section class="donate-text mt-3">
	<div class="container container--medium ">
		<a id="monthly" class="donate-text__anchor"></a>
		<header class="donate-text__header ">
			<h1>Our mission</h1>
		</header>
		<div class="donate-text__content">
			<p>
				Today our mission is all the more important as we maintain a bridge that stretches from our reporters on the ground to our Russian colleagues who have had to leave the country and over to our English-speaking colleagues in Europe. The only change is that recent legislation in Russia makes it imperative to protect some of our sources and contributors. Readers will see fewer bylines, but they will read the same rich stories about people, policies and events; wide-ranging commentary; qualified business analysis and in-depth cultural reporting that they have come to rely on since 1992.
			</p>
		</div>
		<div class="donate-text__content">
			<img width="100%" src="<?php view::url('static'); ?>img/contribute/2022_navalny.jpg" alt="Who we are" />
		</div>
	</div>
</section>

<section class="donate-text mt-3">
	<div class="container container--medium ">
		<header class="donate-text__header ">
			<h1>We need your support now</h1>
		</header>
		<div class="donate-text__content">
			<p>Amid the crackdown as well as a flood of disinformation exacerbated by hundreds of journalists fleeing Russia, it is more important than ever to provide the global community with accessible and informative stories about the region and offer a nuanced view free of stereotypes and prejudices.
			</p>
			<p>Despite the unprecedented challenges, The Moscow Times continues to cover Russia — but we need your support, now even more than ever.
			</p>
			<div class="donate-text__form">
				<?php view::file('contribute/form_tabs') ?>
				<?php //view::file('form/form', ['form' => $form]) ?>
			</div>
			<p>With either a recurring or one-time donation to The Moscow Times, you will be directly supporting the last
				independent English-language news source in Russia. Of course a monthly contribution gives us more security
				throughout the year.</p>
				<p>If you have questions about contributing, please <a href="mailto:development@themoscowtimes.com">send us an email</a>.</p>
		</div>
	</div>
</section>


<section class="donate-text ">
	<div class="container container--medium ">
		<header class="donate-text__header ">
			<h1>About our team</h1>
		</header>
		<div class="donate-text__content">
			<p>
				We are moving our core teams, both English and Russian, to Amsterdam, while keeping contributors on the ground in Moscow, throughout Russia, and in the rapidly growing diasporas abroad. Having the two teams work in the same space together in Amsterdam creates great synergy and makes the reporting in both languages richer and deeper. The transition may take some time, but we are looking forward to celebrating our 30th anniversary in better shape than ever.
			</p>
		</div>
	</div>
</section>


<?php view::end(); ?>