<?php view::extend('account/template/account'); ?>

<?php if (isset($referer) && $referer == 'contribution'): ?>
	<?php view::start('before') ?>
	<div class="message">
		<div class="container container--medium py-5">
			<h1 class="heading align-center">Thank you!</h1>
			<p class="align-center">
				Thank you for your contribution to The Moscow Times!<br />
				If you would like to cancel or change your contribution amount, please visit your personal account.
			</p>
		</div>
	</div>
	<?php view::end(); ?>
<?php endif; ?>


<?php view::start('main'); ?>
<div
	y-use="account.Signin"
	data-signin="<?php view::route('api_signin') ?>"
	data-facebook="<?php //view::route('api_facebook') ?>"
	data-app_id="<?php //view::config('facebook', 'app_id') ?>"
	data-register="<?php view::route('register') ?>"
	data-recover="<?php view::route('recover') ?>"
	data-done="<?php view::route('dashboard') ?>"
>

	<h1 class="heading align-center"><?php view::lang('Sign In') ?></h1>

	<div class="form form--boxed">
		<div class="form__error" y-name="error" style="display:none"></div>
		<div class="form__item">
			<label class="form__label"><?php view::lang('email') ?></label>
			<input class="form__input form__input--text" y-name="identity" type="email" placeholder="" value="<?php view::attr($email ?? '') ?>" />
		</div>
		<div class="form__item">
			<label class="form__label"><?php view::lang('password') ?></label>
			<input class="form__input form__input--text" y-name="credentials" type="password" placeholder="" />
		</div>
		<?php /* <input y-name="permanent" type="checkbox" > <?php view::lang('account.permanent') ?>*/ ?>
		<div class="form__item">
			<div y-name="submit" class="clickable button button--primary"><?php view::lang('Sign In') ?></div>
		</div>

		<?php /* <div y-name="facebook">Inloggen met Facebook</div>*/ ?>
		<div y-name="recover" class="form__link my-2 clickable"><?php view::lang('I\'ve lost my password') ?></div>
		<div class="mb-2">
			<?php view::lang('Don\'t have an account?')  ?> <span y-name="register" class="form__link"><?php view::lang('Create one now') ?></span>
		</div>
	</div>
</div>


<?php view::end(); ?>


