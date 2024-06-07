<?php

namespace Contribute;

use Mollie\API\MollieApiClient;
use Sulfur\Session;
use Sulfur\Email;
use Newsletter\Mailchimp;
use Account\Mollie as MollieAccount;
use Account\Model as Account;
use Url;
use Message\Email as Message;

class Mollie
{

	protected $errors = [];

	protected $client;

	protected $session;

	protected $email;

	protected $mailchimp;

	protected $mollie;

	protected $url;

	protected $message;


	public function __construct(
		MollieApiClient $client,
		Session $session,
		Email $email,
		Mailchimp $mailchimp,
		MollieAccount $mollie,
		Account $account,
		Url $url,
		Message $message
	)
	{
		$this->client = $client;
		$this->session = $session;
		$this->email = $email;
		$this->mailchimp = $mailchimp;
		$this->mollie = $mollie;
		$this->account = $account;
		$this->url = $url;
		$this->message = $message;
	}



	public function errors()
	{
		return $this->errors;
	}



	public function export()
	{
		// get all the recurring payments
		$customerIds = [];
		$parsePayments = function($items) use (& $customerIds){
			foreach($items as $item) {
				if($item->sequenceType == 'recurring') {
					$customerIds[] = $item->customerId;
				}
			}
		};
		$items = $this->client->payments->page();
		$parsePayments($items);
		while($items->hasNext()) {
			$items = $items->next();
			$parsePayments($items);
		}

		$customerIds = array_unique($customerIds);
		$customers = [];
		foreach($customerIds as $customerId) {
			if($customerId){
				$customer = $this->client->customers->get($customerId);
				$period = '';
				foreach($customer->subscriptions() as $sub) {
					if($sub->interval == '1 month') {
						$period = 'monthly';
						break;
					}
					if($sub->interval == '12 months') {
						$period = 'annual';
						break;
					}
				}

				$customers[$customer->email] =[
					'PAYPERIOD' => $period
				];
			}
		}
		return $customers;
	}



	public function customers()
	{
		// get all the payments and group by customers
		$payments = [];
		$parsePayments = function($items) use (& $payments){
			foreach($items as $item) {
				if(! isset($payments[$item->customerId])) {
					$payments[$item->customerId] = [];
				}
				$payments[$item->customerId][] = [
					'id' => $item->id,
					'created' => $item->createdAt,
					'status' => $item->status,
					'amount' => $item->amount->value,
					'currency' => $item->amount->currency,
					'type' => $item->sequenceType,
					'period' => $item->metadata,
				];
			}
		};

		$items = $this->client->payments->page();

		$parsePayments($items);

		while($items->hasNext()) {
			$items = $items->next();
			$parsePayments($items);
		}



		$customers = [];
		$parseCustomers = function($items) use (& $customers, $payments){
			foreach($items as $item) {
				if(! isset($customers[$item->email])) {
					$customers[$item->email] = [
						'name' => $item->name,
						'payments' => [],
						'total' => 0
					];
				}
				$customers[$item->email]['payments'] = array_merge(
					$customers[$item->email]['payments'],
					(isset($payments[$item->id]) ? $payments[$item->id] : [])
				);
			}
		};

		// get the first page
		$items = $this->client->customers->page();
		$parseCustomers($items);


		// get the following pages
		while($items->hasNext()) {
			$items = $items->next();
			$parseCustomers($items);
		}

		$totaled = [];
		foreach($customers as $email => $customer) {
			foreach($customer['payments'] as $payment) {
				if($payment['status'] == 'paid') {
					$customer['total'] += $payment['amount'];
				}
			}
			$totaled[$email] = $customer;

		}
		return $totaled;
	}


	public function create($data, $created, $webhook)
	{
		$valid = true;

		if(empty($data['agree']) || ! $data['agree'] || $data['agree'] == 'false' ) {
			$this->errors['agree'] = 'Please agree to the privacy policy';
			$valid = false;
		}


		if(empty($data['period']) || ! in_array($data['period'], ['once', 'monthly', 'annual'])) {
			$this->errors['period'] = 'No valid period provided';
			$valid = false;
		} else {
			$period = $data['period'];
		}

		$currency = isset($data['currency']) && strtolower($data['currency']) == 'eur' ? 'EUR' : 'USD';

		$amount = $this->parse($data['amount']);
		if(! $amount) {
			$this->errors['amount'] =  'Please provide a valid amount';
			$valid = false;
		}


		if(empty($data['email']) || ! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$this->errors['email'] =  'Please enter a valid email address';
			$valid = false;
		}

		/*
		if(empty($data['country'])) {
			$this->errors['phone'] =  'Please select a country code';
			$valid = false;
		}

		if(empty($data['phone'])) {
			$this->errors['phone'] =  'Please provide a phone number';
			$valid = false;
		}
		*/


		if(empty($data['firstname'])) {
			$this->errors['firstname'] =  'Please provide your first name';
			$valid = false;
		}

		if(empty($data['lastname'])) {
			$this->errors['lastname'] =  'Please provide your last name';
			$valid = false;
		}


		if($valid) {
			$name = preg_replace('#\s{2,}#', ' ', trim($data['firstname'] . ' ' . $data['lastname']));
		}

		if (! $valid) {
			return false;
		}

		// create customer with metadata
		$customer = $this->client->customers->create([
			'name' => $name,
			'email' => $data['email'],
			'metadata' => json_encode([
				// 'phone' =>[
				// 	'country' => $data['country'],
				// 	'number' => $data['phone'],
				// ],
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
			])
		]);

		// store customer locally
		$this->mollie->addCustomer($data['email'], $customer->id);


		if($period === 'once') {
			// single payment
			$payment = $this->client->payments->create([
				'amount' => [
					'currency' => $currency,
					'value' => $amount
				],
				'customerId' =>  $customer->id,
				'description' => 'Single contribution to TMT',
				'metadata' => [
					'processed' => false,
					'period' =>'once'
				],
				'redirectUrl' => $created,
				'webhookUrl'  => $webhook,
			]);

			$this->session->set('mollie_payment_id', $payment->id);
			return $payment->getCheckoutUrl();
		} elseif(in_array($period, ['monthly', 'annual'])) {
			$labels = [
				'monthly' => 'Monthly',
				'annual' => 'Annual',
			];
			$payment = $this->client->payments->create([
				'amount' => [
					'currency' => $currency,
					'value' => $amount
				],
				'customerId' =>  $customer->id,
				'sequenceType' => 'first',
				'description' => $labels[$period] . ' contribution to TMT',
				'metadata' =>  [
					'processed' => false,
					'period' => $period
				],
				'redirectUrl' => $created,
				'webhookUrl'  => $webhook,
			]);

			$this->session->set('mollie_payment_id', $payment->id);
			return $payment->getCheckoutUrl();
		}
	}



	public function paid($paymentId = null)
	{
		if($paymentId === null) {
			$paymentId = $this->session->get('mollie_payment_id', false);
			$this->session->set('mollie_payment_id', '');
		}

		if($paymentId) {
			$payment = $this->client->payments->get($paymentId);
			if($payment && $payment->status === 'paid') {
				return true;
			}
		}
		return false;
	}

	public function info($paymentId)
	{
		$info = [
			'email' => '',
			'recurring' => false,
			'account' => false,
		];

		if($payment = $this->client->payments->get($paymentId)) {
			if($payment->sequenceType == 'first' || $payment->sequenceType == 'recurring') {
				$info['recurring'] = true;
			}
			if($customer = $this->client->customers->get($payment->customerId)) {
				$info['email'] = $customer->email;

				if($account = $this->account->byEmail($customer->email)) {
					$info['account'] = true;
				}
			}
		}
		return $info;
	}


	/**
	 * Check if returned payment is paid or pending
	 */
	public function success($paymentId = null)
	{
		if($paymentId === null) {
			// No payment id means the payment-id got stored in the session and we need to use that
			$paymentId = $this->session->get('mollie_payment_id', false);
			// Remove it so it can't be accidentily be re-used
			$this->session->set('mollie_payment_id', '');
		}

		if($paymentId) {
			$payment = $this->client->payments->get($paymentId);
			if($payment && ($payment->status === 'paid' || $payment->status === 'pending')) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Webhook was called
	 * @param int $paymentId
	 * @param string $recurringWebhook
	 */
	public function process($paymentId, $recurringWebhook = '')
	{
		try {
			// ---------------------------------------
			// Initials values
			// ---------------------------------------
			// Valid periods
			$periods = ['monthly', 'annual'];
			// Mollie recurring intervals
			$intervals = [
				'monthly' => '1 month',
				'annual' => '12 months',
			];
			// Startdates for recurring payments
			$dates = [
				'monthly' => date('Y-m-d', time() + (3600 * 24 * 31)),
				'annual' =>  date('Y-m-d',time() + (3600 * 24 * 365)),
			];
			// Start out by assuming period is 'once'
			$period = 'once';
			// Start out with no processing to be done
			$process = false;

			// ---------------------------------------
			// Check the payment
			// ---------------------------------------
			$payment = $this->client->payments->get($paymentId);
			// Check the payment to get values for $process and $period
			if($payment && $payment->status === 'paid') {
				// We've got a paid payment

				if(is_object($payment->metadata)) {
					// get info from metadata object
					if(isset($payment->metadata->period)) {
						// get the period
						$period = $payment->metadata->period;
					}
					if(! isset($payment->metadata->processed) || $payment->metadata->processed == false) {
						// not yet processsed: We can process it
						$process = true;
					}
				} elseif (is_string($payment->metadata)) {
					// old metadata, it contained only the period as string
					$period = $payment->metadata;
					// process it, although we could be dealing with a refunded one
					$process = true;
				}
			}

			// ---------------------------------------
			// Process the payment
			// ---------------------------------------
			if($process) {
				// ---------------------------------------
				// First update the payment metadata so the payment can't be processed twice
				// ---------------------------------------
				$metadata = $payment->metadata;

				if(is_object($metadata)) {
					// Metadata is an object, just set the processed flag to true and leave the rest.
					$metadata->processed = true;
				} elseif(is_string($payment->metadata)) {
					// Metadata is a string, it's from the old situation where metadata contained only the period
					// Replace with object with processed = true
					$metadata = [
						'processed' => true,
						'period' => $payment->metadata
					];
				} else {
					// Something weird happened. no object and no string, just set it as processed
					$metadata = [
						'processed' => true,
						'period' => $period
					];
				}
				// Set the new metadata and update
				$payment->metadata = $metadata;
				$payment->update();


				// ---------------------------------------
				// Get the customer
				// ---------------------------------------
				$customer = $this->client->customers->get($payment->customerId);

				// Get customer name
				if(
					isset($customer->metadata)
					&& is_object($customer->metadata)
					&& isset($customer->metadata->firstname)
					&& $customer->metadata->firstname
				) {
					$name = $customer->metadata->firstname;
				} else {
					$name = $customer->name;
				}


				if(in_array($period, $periods)) {
					// ---------------------------------------
					// Recurring payment
					// ---------------------------------------

					// E-mail first of recurring
					$this->message->recurringDonationFirst($customer->email, $name, $payment->amount->value, $payment->amount->currency);

					// Get the customer subscriptions, to check if there are none
					$subscriptions = $customer->subscriptions();

					if(count($subscriptions) == 0) {
						// No subscriptions yet, create one
						$subscription = $customer->createSubscription([
							'amount' => [
								'currency' => $payment->amount->currency,
								'value' => $payment->amount->value
							],
							//'times'=> 100,
							'interval' => $intervals[$period],
							'startDate' => $dates[$period],
							'description' => 'Recurring contribution to TMT',
							'webhookUrl'  => $recurringWebhook,
						]);
					}
				} else {
					// ---------------------------------------
					// Single payment
					// ---------------------------------------

					// Email single
					$this->message->donation($customer->email, $name, $payment->amount->value, $payment->amount->currency);
				}
			}

			// ---------------------------------------
			// Update Mailchimp
			// ---------------------------------------
			$this->mailchimp($paymentId);


		} catch (Exception  $e) {
			$this->logger->error($e->getMessage());
		}
	}


	public function recurring($paymentId)
	{
		$payment = $this->client->payments->get($paymentId);

		if($payment && $payment->status === 'paid') {
			$customer = $this->client->customers->get($payment->customerId);
			if(
				isset($customer->metadata)
				&& is_object($customer->metadata)
				&& isset($customer->metadata->firstname)
				&& $customer->metadata->firstname
			) {
				$name = $customer->metadata->firstname;
			} else {
				$name = $customer->name;
			}
			// Send out an e-mail
			$this->message->recurringDonation($customer->email, $name, $payment->amount->value, $payment->amount->currency);
		}

		//  update mailchimp
		$this->mailchimp($paymentId);
	}


	/*
	 * Update record in mailchimp to reflect the changes
	 */
	public function mailchimp($paymentId)
	{
		// get the payment
		$payment = $this->client->payments->get($paymentId);

		if($payment) {
			// get customer
			$customer = $this->client->customers->get($payment->customerId);

			// get email
			$email = $customer->email;

			// get the period
			if($payment->sequenceType == 'recurring') {
				// for a recurring payment, get the period from the subscription
				$period = '';
				foreach($customer->subscriptions() as $sub) {
					if($sub->interval == '1 month') {
						$period = 'monthly';
						break;
					}
					if($sub->interval == '12 months') {
						$period = 'annual';
						break;
					}
				}
			} else {
					// Else get it from the metadata of the payment
				// Payment can be either first of a sequence or a single
				if(is_object($payment->metadata) && isset($payment->metadata->period)) {
					// Metadata is an object woth period property
					$period = $payment->metadata->period;
				} elseif(is_string($payment->metadata)) {
					// Metadata is a string. Old situation where metadata contained only period
					$period = $payment->metadata;
				} else {
					$period = 'once';
				}
			}


			// Data to update in mailchimp
			$data = [
				// 'NAME' => $customer->name,
				'SOURCE' => 'Mollie',
				'PAYDATE' =>  date('Y-m-d',  strtotime($payment->createdAt)),
				'PAYSTATUS' => $payment->status,
				'PAYTYPE' =>  $payment->sequenceType,
				'PAYPERIOD' =>  $period,
				'PAYAMOUNT' =>  $payment->amount->value,
			];

			// When payment is successful, calculate new total
			if($payment->status == 'paid') {
				/*
				// calculate total amount paid for this email
				// Expensive call, check if we can forego this.
				$total = 0;
				$parseCustomers = function($items) use (& $total, $email){
					foreach($items as $item) {
						if($item->email == $email) {
							foreach($item->payments() as $paym) {
								if($paym->status == 'paid') {
									$total += $paym->amount->value;
								}
							}
						}
					}
				};
				// get the first page
				$items = $this->client->customers->page();
				$parseCustomers($items);
				// get the following pages
				while($items->hasNext()) {
					$items = $items->next();
					$parseCustomers($items);
				}
				$data['PAYTOTAL'] = $total;
				 */
			}

			if(! $this->mailchimp->update($email, $data)) {
				$this->mailchimp->insert($email, $data, 'subscribed');
			}
		}
	}




	protected function parse($amount) {
		$clean = preg_replace('#[^0-9\,\.]#', '', $amount);
		if(strlen($clean) == 0) {
			return false;
		}

		$parts = preg_split('#[\,\.]+#', $clean);

		$first = array_shift($parts);
		$last = array_pop($parts);
		$thousands = strlen($first) <= 3;
		foreach($parts as $part) {
			if(strlen($part) !== 3) {
				$thousands = false;
				break;
			}
		}

		if($thousands) {
			// if its a thousands separated
			$amount = $first . implode('', $parts);
			if($last && strlen($last) === 3) {
				// last part is also a thousand, add it
				$amount = $amount . $last;
			} elseif($last && strlen($last) > 0) {
				// else its cents
				$amount = $amount . '.' . substr($last, 0 , 2);
			}
		} elseif(count($parts) > 0) {
			// not thousands, add the first of the remaining parts as cents
			$amount = $first . '.' . substr(array_shift($parts), 0 , 2);
		} elseif($last) {
			// not thousands, add the last part as cents
			$amount = $first + '.' . substr($last, 0 , 2);
		} else {
			$amount = $first;
		}


		return  number_format($amount, 2, '.', '');
	}
}