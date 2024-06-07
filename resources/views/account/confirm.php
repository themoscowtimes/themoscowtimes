<?php view::extend('account/template/account') ?>

<?php view::start('main'); ?>

<div
	y-use="account.Confirm"
	data-customer="<?php view::route('api_customer') ?>"
	data-confirm="<?php view::route('api_confirm') ?>"
	data-token="<?php view::attr($token) ?>"
	data-csrf="<?php view::csrf() ?>"
	data-done="<?php view::route('dashboard') ?>"
>
	<h1 class="heading align-center"><?php view::lang('Account Confirmation') ?></h1>
	<div class="form form--boxed">

		<p class="mb-3 align-center">
			<span class="heading-3"><?php view::lang('Thanks for Signing Up!') ?></span><br />
			<?php view::lang('Were almost done') ?>.
			<?php view::lang('Please tell us a bit more about yourself') ?>.
		</p>

		<div class="form__error" y-name="error" style="display:none"></div>


		<div class="form__item">
			<label class="form__label"><?php view::lang('First name') ?> *</label>
			<input class="form__input form__input--text" y-name="input" data-name="firstname" type="text" placeholder="" />
		</div>

		<div class="form__item">
			<label class="form__label"><?php view::lang('Last name') ?> *</label>
			<input class="form__input form__input--text" y-name="input" data-name="lastname" type="text" placeholder="" />
		</div>

		<div class="form__item">
			<label class="form__label"><?php view::lang('Phone') ?> *</label>
			<div class="row">
				<div class="col-6">
					<select name="country" y-name="input" value="" data-name="phone_country" class="form__input form__input--select" required="">
						<option value=''>Country code</option>
						<?php foreach(fetch::config('countries') as $country): ?>
							<option value="+<?php view::attr($country['phone']) ?>"><?php view::text($country['name']) ?> (+<?php view::text($country['phone']) ?>)</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col">
					<input class="form__input form__input--text" y-name="input" data-name="phone_number" type="text" placeholder="<?php view::lang('Phone number') ?>" />
				</div>
			</div>
		</div>

		<div class="form__item">
			<label class="form__label"><?php view::lang('Your field of work') ?></label>
			<select class="form__input form__input--select" y-name="input" data-name="sector">
				<option value="">Select..</option>
				<?php foreach (fetch::config('sectors') as $sector): ?>
					<option value="<?php view::attr($sector) ?>"><?php view::text($sector) ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="form__item">
			<label class="form__label"><?php view::lang('Date of birth') ?></label>
			<input class="form__input form__input--text" y-name="input"  data-name="birthdate" type="date" placeholder="" />
		</div>

		<div class="form__item">
			<div y-name="submit" class="clickable button button--primary"><?php view::lang('Submit and continue') ?></div>
		</div>

	</div>
</div>

<?php view::end(); ?>