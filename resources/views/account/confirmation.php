<?php view::extend('account/template/account') ?>

<?php view::start('before') ?>
	<div class="message">
		<div class="container container--medium py-5">
			<h1 class="heading align-center"><?php view::lang('Confirm your account'); ?></h1>
			<p class="align-center">
				We have sent you an email with a confirmation link. Please click on it to confirm your account. <br />
				Or click resend the email if you don't see it in your mailbox.
			</p>
		</div>
	</div>
<?php view::end(); ?>



<?php view::start('main'); ?>
<div
	y-use="account.Confirmation"
	data-confirmation="<?php view::route('api_confirmation') ?>"
>
	<div class="form">
		<div class="align-center">
			<?php if ($authenticated): ?>
				<div class="button" y-name="confirmation"><?php view::lang('Resend the email'); ?></div>
				<div class="form__message" y-name="sent" style="display:none"><?php view::lang('The email has been sent'); ?></div>
				<div class="form__error" y-name="error" style="display:none"></div>
			<?php else: ?>
				<a class="button" href="<?php view::route('signin') ?>"><?php view::lang('Sign in to resend the email'); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php view::end(); ?>