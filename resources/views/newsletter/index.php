<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'page-item') ?>

<?php view::start('main') ?>

<div class="container">
	<div class="row-flex gutter-2">
		<div class="col">
			<article class="page" style="max-width:800px; margin: 0 auto 48px;">
				<header class="page__header">
					<span class="newsletters__header header--style-2">
						<img src="<?php view::url('static'); ?>img/banner_tmt_weekly.jpg" alt="TMT Newsletter" />
					</span>
				</header>
				<section class="newsletters" y-use="Newsletters">
					<p class="newsletters__disclaimer">Our weekly newsletter contains a hand-picked selection of news, features, analysis and more from The Moscow Times. You will receive it in your mailbox every Friday. Never miss the latest news from Russia.</p>
					<p class="newsletters__disclaimer"><em>Subscribers agree to the <a
								href="<?php view::url('base')?>page/privacy-policy">Privacy Policy</a></em></p>
					<?php if (isset($campaigns)): ?>
					<p class="newsletters__disclaimer">
						<a href="<?php view::text($campaigns); ?>"><em>Preview</em></a>
					</p>
					<?php endif; ?>
					<div class="newsletters__form" y-name="form">
						<div y-name="url" data-url="<?php view::route('newsletter'); ?>" id="newsletters">
							<input type="email" placeholder="<?php view::lang('Your email'); ?>" y-name="email" class="mb-1" />
							<input type="text" placeholder="<?php view::lang('Your name'); ?>" y-name="name" class="mb-1" />
							<button class="button button--color-2" y-name="submit"><?php view::lang('Subscribe'); ?></button>
							<div class="newsletters__error" y-name="error" style="display:none"></div>
							<div class="newsletters__message" y-name="done" style="display:none">Thanks for signing up!</div>
						</div>
					</div>
					<p class="newsletters__disclaimer"><em>If you are having trouble subscribing through our form, please follow this <a href="https://mailchi.mp/themoscowtimes/subscribe">link</a>.</em></p>
				</section>
			</article>
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
<?php view::end(); ?>