<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'page-item') ?>

<?php view::start('main') ?>
	<div class="container">
		<div class="row-flex gutter-2">
			<div class="col" >
				<article class="page">

					<header class="page__header ">
						<h1><?php view::lang('newsletter.title'); ?></h1>
					</header>


					<div class="page__content-container">
						<div class="page__content">
							<div y-use="Newsletterform" data-url="<?php view::route('newslettersubmit') ?>">
								<div y-name="error" class="form__error" style="display:none;"></div>
								<div y-name="done" style="display:none;"><?php view::lang('newsletter.subscribed')?></div>
								<?php view::file('form/form', ['form' => $form]) ?>
							</div>
						</div>

					</div>

				</article>
			</div>
			<div class="col-auto hidden-sm-down">
				<aside class="sidebar" style=""></aside>
			</div>
		</div>
	</div>
<?php view::end(); ?>