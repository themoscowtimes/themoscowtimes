<footer class="footer fancyfooter">

	<div class="container">
		<div class="footer__inner">
			<div class="footer__logo mb-3">
				<a href="<?php view::url('base'); ?>" class="footer__logo__wrapper"
					title="The Moscow Times - Independent News from Russia">
					<?php if (isset($_GET["amp"]) == 1): ?>
						<amp-img src="<?php view::url('static'); ?>/img/logo_tmt_amp-1710_2023-1.png" alt="The Moscow Times" layout="responsive" height="126" width="640"></amp-img>
					<?php else: ?>
						<img src="<?php view::url('static'); ?>img/logo_tmt_30_yo.svg" alt="The Moscow Times">
					<?php endif; ?>
				</a>
			</div>
			<div class="footer__main"></div>
			<div class="footer__bottom">
				&copy; The Moscow Times, all rights reserved.
			</div>

		</div>

	</div>
</footer>