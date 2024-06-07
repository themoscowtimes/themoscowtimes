<section class="donate-form donate-form--style-1">
	<form
		class="donate-form__form"
		y-use="contribute.Donate"
		data-url="<?php view::route('contribute_submit') ?>"
		data-period="<?php view::attr($period ?? 'monthly') ?>"
		data-amount="<?php view::attr($amount ?? '50') ?>"
		data-currency="usd"
		y-name="donate-form"
	>
		<div class="donate-form__wrapper">
			<div class="input-wrapper">
				<div class="mb-3">
					<div class="donate-form__row">
						<?php foreach ($amounts as $amount => $label): ?>
							<div class="donate-form__column">
								<a class="donate-form__amount"
								   id="amount_<?php view::attr($period ?? 'monthly') ?>_<?php view::attr($amount) ?>"
								   y-name="amount amount_<?php view::attr($amount) ?>" data-amount="<?php view::attr($amount) ?>"><span
										style="display:none">fb_tracking</span>
									<?php if ($label != 'Other'): ?><span y-name="curr-label">$</span><?php endif; ?>
									<span y-name="curr-value"><?php view::text($label) ?></span></a>
							</div>
						<?php endforeach; ?>
					</div>
					<input class="mt-3" type="text" y-name="other" placeholder="Other amount" style="display: none;">
				</div>
			</div>

			<div class="row-fluid">
				<div class="col-6">
					<div class="input-wrapper required " y-name="input-wrapper">
						<label class="form__label">First name&nbsp;&nbsp;<em>(required)</em></label>
						<input type="text" name="firstname" y-name="firstname" value="" placeholder="First name" class="form-control" required="">
					</div>
				</div>
				<div class="col-6">
					<div class="input-wrapper required " y-name="input-wrapper">
						<label class="form__label">Last name&nbsp;&nbsp;<em>(required)</em></label>
						<input type="text" name="lastname" y-name="lastname" value="" placeholder="Last name" class="form-control" required="">
					</div>
				</div>
			</div>

			<div class="input-wrapper required " y-name="input-wrapper">
				<label class="form__label">E-mail address&nbsp;&nbsp;<em>(required)</em></label>
				<input type="text" name="email" y-name="email" value="" placeholder="E-mail address" class="form-control" required="">
			</div>

			<?php /*
			<div class="input-wrapper required " y-name="input-wrapper">
				<label class="form__label">Phone&nbsp;&nbsp;<em>(required)</em></label>
				<div class="donate-form__phone">
					<select name="country" y-name="country" value="" class="form-control" required="">
						<option value=''>Country code</option>
						<?php foreach (fetch::config('countries') as $country): ?>
							<option value="+<?php view::attr($country['phone']) ?>"><?php view::text($country['name']) ?> (+<?php view::text($country['phone']) ?>)</option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="phone" y-name="phone" value="" placeholder="Phone number" class="form-control donate-form__phonenumber" required="">
				</div>
			</div>
			*/ ?>
			
			<div class="input-wrapper required " y-name="input-wrapper">
				<label class="privacy" y-name="privacy">
					<div>
						<input type="checkbox" y-name="agree" value="1" required>
						<span class="small">
							I agree to the <a href="https://www.themoscowtimes.com/page/privacy-policy" target="_blank" style="color: #3263c0;">privacy policy</a>.
						</span>
					</div>
				</label>
			</div>

			<a class="contribute__submit" id="contribute-submit-form" y-name="submit">Contribute</a>

			<br />

			<?php /*<small style="font-size: 0.8rem; line-height: 1em;">
				You can cancel or change your contribution at any time by logging into <a style="color: #3263c0;" href="<?php view::url('base'); ?>account/signin">your personal account.</a>
			</small> */?>
			<small style="font-size: 0.8rem; line-height: 1em;">
				You can cancel or change your donation amount anytime by sending an email to <a style="color: #3263c0;" href="mailto:support@themoscowtimes.com">support@themoscowtimes.com</a>.
			</small>
		</div>
	</form>
</section>