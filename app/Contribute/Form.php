<?php

namespace Contribute;

use Sulfur\Form\Builder;

class Form extends Builder
{


	public function elements()
	{

		return [
			['period', 'contribute/period', 'label' => false],
			['amount_once', 'contribute/amount', 'label' => false, 'options' => [25, 50, 100, 250, 'other'], 'message' => ['amount' => 'Geen bedrag gekozen']],
			['amount_monthly', 'contribute/amount', 'label' => false, 'options' => [10, 15, 30, 'other'], 'message' => ['amount' => 'Geen bedrag gekozen']],
			['amount_annual', 'contribute/amount', 'label' => false, 'options' => [50, 100, 250, 500, 'other'], 'message' => ['amount' => 'Geen bedrag gekozen']],

			['email', 'text', 'label' => 'E-mail address', 'required' => true, 'message' => ['required' => 'Provide an e-mail address', 'email' => 'Provide a valid e-mail address' ]],
			// ['phone', 'text', 'label' => 'Phone number', 'required' => true, 'message' => ['required' => 'Provide a phone number', 'email' => 'Provide a valid phone number' ]],
			['firstname', 'text', 'label' => 'First name', 'required' => true, 'message' => ['required' => 'Please provide your first name']],
			['lastname', 'text', 'label' => 'Last name', 'required' => true, 'message' => ['required' => 'Please provide your last name']],
			['submit', 'contribute/submit', 'label' => false],
		];
	}


	public function rules($form)
	{

	}

	public function layout()
	{
		return [
			['contribute/form', [
				'period',
				['contribute/wrapper', [
					'amount_once',
					'amount_monthly',
					'amount_annual',
					'email',
					// 'phone',
					'firstname',
					'lastname',
					'submit',
				]]
			]]
		];
	}
}