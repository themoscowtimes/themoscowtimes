<?php
view::extend('account/html');
view::start('body');
?>

<main
	class="main main--dashboard"
	y-use="account.Dashboard"
	data-csrf="<?php view::csrf() ?>"
	data-section="account"
	data-signout="<?php view::route('api_signout') ?>"
	data-signin="<?php view::route('signin') ?>"
	data-signoff="<?php view::route('api_signoff') ?>"
	data-account="<?php view::route('api_account') ?>"
	data-donations="<?php view::route('api_donations') ?>"
	data-update="<?php view::route('api_update') ?>"
	data-donate="<?php view::route('contribute') ?>"
>


	<script type="text/html" y-name="menu">
		<div class="header__profile profile">
			<span class="profile__name" y-name="expand">
				{{ name.substr(0,1).toUpperCase() }}
			</span>
			<div class="header__profile-menu profile__menu" y-name="options" style="display:none;" >
				<span class="profile__link" y-on="click stop:signout"><?php view::lang('Signout') ?></span>
			</div>
		</div>
	</script>

	<script type="text/html" y-name="account">
		<div>
			<h1 class="heading-1 mb-2">My account</h1>
			<div class="wysiwyg">
				<p>Manage you account settings</p>
			</div>
			<section class="section mb-3">
				<h2 class="section__title"><?php view::lang('Your profile') ?></h2>

				<div class="panel" y-name="email">
					<div class="panel__header">
						<h3 class="panel__title"><?php view::lang('Email address') ?></h3>
						<?php /*<span class="panel__action" y-on="click:email"><?php view::lang('Change') ?></span> */ ?>
					</div>
					<div class="panel__body">{{email}}</div>
				</div>
				<div class="panel form" y-name="email-update" style="display:none">
					<div class="form__item">
						<label class="form__label"><?php view::lang('New email address') ?></label>
						<input class="form__input form__input--text" type="text" y-name="value" />
						<div y-name="error" class="form__error" style="display:none"></div>
					</div>
					<div class="form__item">
						<div class="button button--primary" y-on="click:emailUpdate"><?php view::lang('Save') ?></div>
						<div class="button button--secondary" y-on="click:emailCancel"><?php view::lang('Cancel') ?></div>
					</div>
				</div>

				<div class="panel" y-name="information">
					<div class="panel__header">
						<h3 class="panel__title"><?php view::lang('Your information') ?></h3>
						<span class="panel__action" y-on="click:information"><?php view::lang('Update') ?></span>
					</div>
					<div class="panel__body">Name: <strong>{{ typeof firstname === 'undefined' ? '' : firstname || '' }} {{ typeof lastname === 'undefined' ? '' : lastname || '' }}</strong></div>
					<div class="panel__body">Phone: <strong>{{ typeof phone_country === 'undefined' ? '' : phone_country || '' }} {{ typeof phone_number === 'undefined' ? '' : phone_number || '' }}</strong></div>
					<div class="panel__body">Birthdate: <strong>{{ typeof birthdate === 'undefined' ? '' :  birthdate | format}}</strong></div>
					<div class="panel__body">Field of work: <strong>{{ typeof sector === 'undefined' ? '' : sector || '' }}</strong></div>
				</div>

				<div class="panel form" y-name="information-update" style="display:none">
					<div class="form__item">
						<label class="form__label"><?php view::lang('First name') ?></label>
						<input class="form__input form__input--text" type="text" y-name="input" data-name="firstname" />
					</div>
					<div class="form__item">
						<label class="form__label"><?php view::lang('Last name') ?></label>
						<input class="form__input form__input--text" type="text" y-name="input" data-name="lastname" />
					</div>
					<div class="form__item">
						<label class="form__label"><?php view::lang('Phone number') ?></label>
						<div class="row">
							<div class="col-4">
								<select name="country" y-name="input" value="" data-name="phone_country" class="form__input form__input--select" required="">
									<option value=''>Country code</option>
									<?php foreach(fetch::config('countries') as $country): ?>
										<option value="+<?php view::attr($country['phone']) ?>"><?php view::text($country['name']) ?> (+<?php view::text($country['phone']) ?>)</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col">
								<input class="form__input form__input--text" y-name="input" data-name="phone_number" type="text" placeholder="<?php view::lang('Phone number') ?>" value="{{phone_number || ''}}" />
							</div>
						</div>
					</div>
					<div class="form__item">
						<label class="form__label"><?php view::lang('Field of work') ?></label>
						<select class="form__input form__input--select" y-name="input" data-name="sector">
							<option>Pick a sector</option>
							<?php foreach (fetch::config('sectors') as $sector): ?>
								<option value="<?php view::attr($sector) ?>"><?php view::attr($sector) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form__item">
						<label class="form__label"><?php view::lang('Date of birth') ?></label>
						<input class="form__input form__input--text" type="date" y-name="input" data-name="birthdate" />
					</div>
					<div class="form__item">
						<div class="button button--primary" y-on="click:informationUpdate"><?php view::lang('Save') ?></div>
						<div class="button button--secondary" y-on="click:informationCancel"><?php view::lang('Cancel') ?></div>
					</div>
				</div>


				<div class="panel" y-name="password">
					<div class="panel__header">
						<h3 class="panel__title"><?php view::lang('Password') ?></h3>
						<span class="panel__action" y-on="click:password">Change</span>
					</div>
					<div class="panel__body">
						<p>*********</p>
					</div>
				</div>
				<div class="panel form" y-name="password-update" style="display:none">
					<div class="form__item">
						<label class="form__label"><?php view::lang('New password') ?></label>
						<input class="form__input form__input--text" type="password" y-name="value" />
						<div y-name="error" class="form__error" style="display:none"></div>
					</div>
					<div class="form__item">
						<div class="button button--primary" y-on="click:passwordUpdate"><?php view::lang('Save') ?></div>
						<div class="button button--secondary" y-on="click:passwordCancel"><?php view::lang('Cancel') ?></div>
					</div>
				</div>
			</section>


			<section class="section mb-3">
				<h2 class="section__title">Your account</h2>
				<div class="panel">
					<div class="panel__header">
						<h3 class="panel__title">Status</h3>
						<span class="panel__action" y-on="click:signoff"><?php view::lang('Delete my account') ?></span>
					</div>
					<div class="panel__body">
						<p>Your account is active</p>
					</div>
				</div>
			</section>
		</div>
	</script>




	<script type="text/html" y-name="donations">
		<div>
			<h1 class="heading-1 mb-2">My donations</h1>
			<div class="wysiwyg">
				<p>Information about my donations</p>
			</div>
			<section class="section mb-3">
				<h2 class="section__title"><?php view::lang('Your recurring donations') ?></h2>
				{% if donations.length == 0 %}
					<div class="panel">
						<div class="panel__header">
							<h3 class="panel__title"><?php view::lang('You don\'t have any recurring donations') ?></h3>
							<span class="panel__action" y-on="click:donate">Become a contributor</span>
						</div>
						<div class="panel__info mt-3">
							<p>With as little as $9 Monthly donation to The Moscow Times from each contributor, we could do more investigative journalism. </p>
						</div>
					</div>
				{% else %}
					{% each donations as donation %}
						<div class="panel" y-name="donation">
							<div y-name="donation-info">
								<div class="panel__header">
									<h3 class="panel__title">{{donation.currency}} <span y-name="amount">{{donation.amount}}</span> period: {{donation.interval}}</h3>
									<span class="panel__action" y-on="click:donationUpdate">Update</span>&nbsp;
								</div>
								<div class="panel__body">
									Started at: {{donation.start|format}}
								</div>
							</div>
							<div class="form" y-name="donation-update" style="display:none" >
								<div class="form__item">
									<label class="form__label"><?php view::lang('New amount') ?></label>
									<input class="form__input form__input--text" type="text" y-name="value" />
									<div y-name="error" class="form__error" style="display:none"></div>
								</div>
								<div class="form__item">
									<div class="button button--primary" y-on="click:donationUpdateUpdate" data-url="{{donation.update}}"><?php view::lang('Update recurring donation') ?></div>
									<div class="button button--secondary" y-on="click:donationUpdateCancel"><?php view::lang('Cancel') ?></div>
								</div>
								<div class="mt-3">
									<span class="panel__action" y-on="click:donationCancel" data-url="{{donation.cancel}}"><?php view::lang('Stop my recurring donation') ?></span>
								</div>
							</div>
						</div>
					{% endeach %}

				{% endif %}
			</section>
		</div>
	</script>


	<script type="text/html" y-name="subscriptions">
		<div>
			<h1 class="heading-1 mb-2">My subscriptions</h1>
			<div class="wysiwyg">
				<p>Information about my subscriptions</p>
			</div>
		</div>
	</script>


	<header class="header" y-name="header">
		<div class="header__toggle hidden-md-up" y-name="sidemenu-expand">
			<svg class="header__toggle__svg" viewBox="0 0 100 80">
				<rect width="100" height="16" ></rect>
				<rect y="30" width="100" height="16"></rect>
				<rect y="60" width="100" height="16"></rect>
			</svg>
		</div>
		<a href="<?php view::route('home') ?>" class="header__logo">
			<img src="<?php view::url('static') ?>img/mtblack.svg" class="header__logo__img" />
		</a>
	</header>

	<div class="container container--large main__body pt-5">
		<div class="row ">
			<div class="col-3 col-12-sm-down">
				<div class="sidemenu">
					<div class="sidemenu__overlay" style="display: none;" y-name="sidemenu-overlay"></div>
					<div class="sidemenu__menu" y-name="sidemenu">
						<div class="sidemenu__close hidden-md-up" y-name="sidemenu-close">
							<span class="sidemenu__close__icon">&#215;</span>
						</div>
						<nav>
							<ul class="sidemenu__list">
								<li class="sidemenu__item" y-name="sidemenu-section" data-section="account">
									<div title="<?php view::lang('My account') ?>" y-name="sidemenu-link" class="clickable sidemenu__link sidemenu__link--active"><?php view::lang('My Account') ?></div>
								</li>

								<li class="sidemenu__item" y-name="sidemenu-section" data-section="donations">
									<div title="<?php view::lang('donations') ?>" y-name="sidemenu-link" class="clickable sidemenu__link"><?php view::lang('Donations') ?></div>
								</li>

								<?php /*
								<li class="sidemenu__item" y-name="_section" data-section="subscriptions" style="opacity: 0.5">
									<div title="<?php view::lang('subscriptions') ?>" class="clickable sidemenu__link"><?php view::lang('Subscriptions') ?></div>
								</li>
								*/ ?>
								<li class="sidemenu__item" y-name="signout">
									<div title="<?php view::lang('Signout') ?>" class="clickable sidemenu__link"><?php view::lang('Signout') ?></div>
								</li>
							</ul>
						</nav>
						<hr style="margin: 12px 0; border-top: 1px solid #eee;" />
						<ul class="sidemenu__list">
							<li class="sidemenu__item">
							<a href="<?php view::url('base'); ?>in-print" class="sidemenu__link">Print Editions</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-9 col-12-sm-down" y-name="content"></div>
		</div>
	</div>

	<?php view::file('account/footer'); ?>
</main>

<?php view::end(); ?>