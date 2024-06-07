<div class="site-header py-3 hidden-xs">
	<a href="<?php view::url('base'); ?>" class="site-header__logo" title="The Moscow Times - Independent News from Russia" >
		<img src="<?php view::url('static'); ?>img/logo_tmt_30_yo.svg" alt="The Moscow Times"  />
	</a>

	<?php view::contribute() ?>


	<div class="site-header__account">
		<?php view::file('common/account'); ?>
	</div>


</div>