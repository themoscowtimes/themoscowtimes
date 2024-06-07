<?php view::extend('template/default'); ?>

<?php view::block('seo', fetch::seo('contribute')) ?>

<?php view::block('body.class', 'content-item--full-header') ?>

<?php view::start('main') ?>
<section class="contribute py-4">
	<div class="container">
		<div class="row-flex">
			<div class="col order-container-form">
				<?php view::file('form/form', ['form' => $form]) ?>
			</div>
			<div class="col order-container-content">
				<div class="mt-3-sm-up">
					<h1>Support The Moscow Times!</h1>
					<p>
                        The Moscow Times is the only Moscow-based, English-language, independent source of news, business, arts and opinion about Russia.
                        <br /><br />Since 1992, our multinational staff of editors and journalists has been bringing objective, balanced, fascinating and sometimes just plain fun articles, photos, videos and podcasts to you all around the world, every day.
                        <br /><br />Contribute today to keep the news coming!
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

<?php view::end(); ?>



