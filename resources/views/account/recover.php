<?php view::extend('account/template/account') ?>

<?php view::start('main'); ?>

<div
	y-use="account.Recover"
	data-recover="<?php view::route('api_recover') ?>"
	data-signin="<?php view::route('signin') ?>"
>
	<h1 class="heading align-center"><?php view::lang('Password Recovery') ?></h1>
	<div class="form form--boxed">
		<div class="form__error" y-name="error" style="display:none"></div>
		<div y-name="done" class="form__message "style="display:none"><?php view::lang('An email has been sent to reset your password') ?></div>
		<div y-name="form">
			<div class="form__item">
				<label class="form__label"><?php view::lang('Email address') ?></label>
				<input class="form__input form__input--text" y-name="email" type="text" />
			</div>
			<div class="form__item">
				<div y-name="submit" class="clickable button button--primary"><?php view::lang('Send') ?></div>
			</div>
		</div>
		<div y-name="signin" class="form__link my-2 clickable"><?php view::lang('Back to sign in') ?></div>
	</div>
</div>

<?php view::end(); ?>