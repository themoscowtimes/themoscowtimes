<?php
view::extend('template/default');
view::block('seo', fetch::seo('contribute'));
view::block('body.class', 'content-item--full-header');
view::start('main');
?>

<section class="contribute py-4" y-use="contribute.Done">
	<div class="container">
		<div class="row-flex">
			<div class="col-6 order-container-content">
				<div class="mt-3-sm-up">
					<?php if (isset($success) && $success): ?>
						<h1>Thank You!</h1>
						<p>
						Thank you for your contribution to The Moscow Times! You should receive an email confirming your payment.
						If you wish to cancel or change your contribution amount, please email us at: <a href="mailto:development@themoscowtimes.com">development@themoscowtimes.com</a>.
						</p>
					<?php else: ?>
						<h1>Oops</h1>
						<p>
							Something went wrong when processing you contribution, please <a href="<?php view::route('contribute') ?>">try again</a>. If you have any questions about your contribution, please email us at: <a href="mailto:development@themoscowtimes.com">development@themoscowtimes.com</a>.
						</p>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</section>

<?php view::end(); ?>