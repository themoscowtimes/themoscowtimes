<?php view::extend('account/template/account'); ?>


<?php if (isset($referer) && $referer == 'contribution'): ?>
	<?php view::start('before') ?>
	<div class="message">
		<div class="container container--medium py-5">
			<h1 class="heading align-center">Thank you!</h1>
			<p class="align-center">
				Thank you for your contribution to The Moscow Times!<br />
				Please create your personal account now to be able to cancel or to change your contribution amount.
			</p>
		</div>
	</div>
	<?php view::end(); ?>
<?php endif; ?>


<?php view::start('main') ?>
<div
	y-use="account.Register"
	data-register="<?php view::route('api_register') ?>"
	data-signin="<?php view::route('signin') ?>"
	data-done="<?php view::route('dashboard') ?>"
>

	<h1 class="heading align-center"><?php view::lang('Create an Account') ?></h1>

	<div class="form form--boxed">
		<div class="form__error" y-name="error" style="display:none"></div>
		<div class="form__item">
			<label class="form__label"><?php view::lang('email') ?></label>
			<input class="form__input form__input--text" y-name="email" type="text" placeholder="" value="<?php view::attr($email ?? '') ?>"  />
		</div>
		<div class="form__item">
			<label class="form__label"><?php view::lang('password') ?></label>
			<input class="form__input form__input--text" y-name="password" type="password" placeholder="" />
		</div>
		<div class="form__item">
			<label style="display: flex; align-items: center;" for="terms">
				<input type="checkbox" y-name="agreed" style="margin-right: 8px;" id="terms" name="terms">
				<?php view::lang('I agree to the') ?>&nbsp;<a style="border-bottom: 1px solid;" href="<?php view::route('terms') ?>" target="_blank"><?php view::lang('terms and conditions') ?></a>
			</label>
		</div>
		<div class="form__item">
			<div y-name="submit" class="clickable button button--primary"><?php view::lang('Create an account') ?></div>
		</div>
		<div y-name="signin" class="form__link"><?php view::lang('Have an account already? Sign in') ?></div>
	</div>
</div>
<?php view::end(); ?>