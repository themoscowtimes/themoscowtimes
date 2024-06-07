<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="googlebot" content="noarchive">

		<base href="<?php view::url('base'); ?>" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="theme-color" content="#5882b5">
		<link rel="shortcut icon" href="<?php view::url('static'); ?>img/icons/favicon.ico">

		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon-precomposed" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-57x57.png">


		<!-- load stylesheets -->
		<link type="text/css" href="<?php view::url('static'); ?>css/account.css?v=<?php view::version() ?>" rel="stylesheet" media="screen" />

		<!-- Other CSS assets -->
		<?php foreach(view::assets('css') as $asset): ?>
			<link type="text/css" href="<?php view::attr($asset) ?>" rel="stylesheet" media="screen" />
		<?php endforeach; ?>
	</head>


	<body class="<?php view::block('body.class', '') ?>">
		<?php view::block('body',''); ?>

		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
		<script src="<?php view::url('static') ?>vendor/jquery/Timeago.js"></script>

		<!-- Other JS assets -->
		<?php foreach(view::assets('js') as $asset): ?>
			<script src="<?php view::attr($asset) ?>"></script>
		<?php endforeach; ?>

		<script
			type="text/javascript"
			src="<?php view::url('static') ?>vendor/yellow/Yellow.js"
			<?php if(fetch::env('env') === 'development'): ?>
				data-main="<?php view::url('base') ?>js.php?rebuild=main"
				data-src="<?php view::url('base') ?>js.php?build=main&file="
			<?php else: ?>
				data-main="<?php view::url('static') ?>js/main.js?v=<?php view::version() ?>"
				data-src="<?php view::url('static') ?>js/"
			<?php endif; ?>
			data-console="<?php view::attr(fetch::env('env') === 'development' ? '1' : '0'); ?>"
		></script>
	</body>
</html>