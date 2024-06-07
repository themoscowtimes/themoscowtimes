<?php view::extend('account/template/account') ?>

<?php view::start('main'); ?>

<div
	y-use="account.Reset"
	data-reset="<?php view::route('api_reset') ?>"
	data-token="<?php view::attr($token) ?>"
	data-csrf="<?php view::csrf() ?>"
	data-signin="<?php view::route('signin') ?>"
>
	<h1 class="heading align-center" ><?php view::lang('Reset Your Password') ?></h1>
	<div class="form form--boxed">
		<div  class="form__error" y-name="error" style="display:none"></div>

		<div y-name="done" style="display:none"><?php view::lang('Your password has been reset') ?></div>

		<div y-name="form">
			<div class="form__item">
				<label class="form__label"><?php view::lang('New password') ?></label>
				<input class="form__input form__input--text" y-name="password" type="password" placeholder="" /><br />
			</div>
			<div class="form__item">
				<div y-name="submit" class="clickable button button--primary"><?php view::lang('submit') ?></div><br />
			</div>
		</div>
		<div y-name="signin" class="form__link my-2"><?php view::lang('Back to sign in') ?></div>
	</div>
</div>

<?php view::end(); ?>
