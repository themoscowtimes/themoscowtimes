<?php
view::extend('account/html');
view::start('body');
?>
<main class="main">
	<header class="header">
		<a href="<?php view::route('home') ?>" class="header__logo">
			<img src="<?php view::url('static') ?>img/mtblack.svg" class="header__logo__img" />
		</a>
	</header>

	<?php view::block('before'); ?>

	<div class="main__body container container--small py-5">
		<?php view::block('main'); ?>
	</div>

	<?php view::file('account/footer'); ?>
</main>
<?php view::end(); ?>