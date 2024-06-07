<?php view::extend('html') ?>

<?php view::start('body'); ?>
<?php view::block('billboard'); ?>

<?php view::file('contribute/modal'); ?>

<div class="container">
  <?php view::header('main') ?>
</div>

<div class="container">
	<?php view::menu('main') ?>
</div>

<div class="container">
	<?php view::contribute('sm') ?>
</div>

<?php view::block('main'); ?>

<?php view::menu('footer') ?>

<?php view::end(); ?>