<div y-use="contribute.Tabs" data-active="active">

	<div class="container">
			<div class="donate-form__currency">
				<select class="donate-form__currency__selector" name="currency" id="currency" y-name="currency-selector">
					<option value="usd">International US$</option>
					<option value="eur">Europe €</option>
				</select>
			</div>
		<div class="row-fluid">
		
			
			<div class="col-3">
				<div class="donate-form__period" y-name="tab" data-content="once">Once</div>
			</div>
			<div class="col-3">
				<div class="donate-form__period active" y-name="tab" data-content="monthly">Monthly</div>
			</div>
			<div class="col-3">
				<div class="donate-form__period" y-name="tab" data-content="annual">Annual</div>
			</div>
			<div class="col-3">
				<div class="donate-form__period donate-form__paypal">
					<a href="https://www.paypal.com/donate/?hosted_button_id=Q4P8T39WFC24L" target="_blank" rel="noopener noreferrer">
						<img src="https://www.paypalobjects.com/paypal-ui/logos/svg/paypal-mark-color.svg" style="height: 24px; width: 24px; margin-right: 8px;">
						<span>PayPal</span>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="content" y-name="content once" style="display:none">
		<?php view::file('contribute/form', [
			'period' => 'once',
			'amounts' =>  ['25' => '25', '50' => '50', '100' => '100' ,'250' => '250' ,'other' => 'Other'],
			'amount' => 50
		]) ?>
	</div>

	<div class="content" y-name="content monthly">
		<?php view::file('contribute/form', [
			'period' => 'monthly',
			'amounts' =>  ['10' => '10', '15' => '15', '30' => '30' ,'other' => 'Other'],
			'amount' => 15
		]) ?>
	</div>

	<div class="content" y-name="content annual" style="display:none">
		<?php view::file('contribute/form', [
			'period' => 'annual',
			'amounts' =>  ['50' => '50', '100' => '100', '250' => '250' ,'500' => '500' ,'other' => 'Other'],
			'amount' => 100
		]) ?>

	</div>
</div>