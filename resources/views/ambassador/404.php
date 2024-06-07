
<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'page-item') ?>

<?php view::start('main') ?>
	<div class="container">
		<div class="row-flex gutter-2">
			<div class="col" >
				<article class="page">
					<header class="page__header ">
						<h1>Ошибка 404</h1>
						<h2>Страница не найдена!</h2>
					</header>
					<div class="page__intro">
						<p>Вы находитесь здесь, потому что ввели адрес страницы, которая уже не существует или была перемещена по другому адресу. Воспользуйтесь поиском или <a href="/" title="<?php view::lang('Home'); ?>">вернитесь на заглавную страницу</a>.</p>
					</div>
				</article>
			</div>
		</div>
	</div>
<?php view::end(); ?>