<?php

namespace Account;

use Mollie\API\MollieApiClient;
use Sulfur\Data;

class Mollie
{

	protected $error = 'error';


	public function __construct($client, Data $data, Encrypt $encrypt)
	{
		$this->client = $client;
		$this->data = $data;
		$this->encrypt = $encrypt;
	}


	public function error()
	{
		return $this->error;
	}


	public function subscriptions($email, $cached = false)
	{
		$emailKey = $this->emailKey($email);
		$db = $this->data->database();
		if($cached) {
			// Get from cache: only id available
			$rows = $db->select('id', 'subscriptions')
			->from('mollie')
			->where('email', $emailKey)
			->result();

			if(count($rows) > 0) {
				$subscriptions = json_decode($this->encrypt->decrypt($rows[0]['subscriptions']), true);
				if(! is_array($subscriptions)) {
					$subscriptions = [];
				}
			} else {
				$subscriptions = [];
			}
			return $subscriptions;
		} else {
			// Get from mollie and store cache
			$subscriptions = [];
			$cache = [];

			foreach($this->customers($email) as $customer) {
				// Get subscriptions from mollie
				foreach($this->client->customers->get($customer)->subscriptions() as $subscription) {
					if($subscription->status == 'active') {
						// create a token
						$id = $this->token();

						// Add to cache
						$cache[$id] = [
							'subscription' => $subscription->id,
							'customer' => $customer,
						];

						// Add to output
						$meta = $subscription->metadata;
						if(is_object($meta) && isset($meta->type)) {
							$type = $meta->type;
						} else {
							$type = 'donation';
						}
						$subscriptions[$id] = [
							'start' => $subscription->startDate,
							'interval' => $subscription->interval,
							'amount' => $subscription->amount->value,
							'currency' => $subscription->amount->currency,
							'type' => $type
						];

					}
				}
			}

			// Update cache
			$db->update('mollie')
			->set([
				'subscriptions' => $this->encrypt->encrypt(json_encode($cache))
			])
			->where('email', $emailKey)
			->execute();


			// Return subs
			return $subscriptions;
		}
	}


	public function cancel($email, $id)
	{
		// get cached subscriptions
		$subscriptions = $this->subscriptions($email, true);

		if(isset($subscriptions[$id])) {
			if($customer = $this->client->customers->get($subscriptions[$id]['customer'])) {
				if($subscription = $customer->cancelSubscription($subscriptions[$id]['subscription'])) {
					return true;
				} else {
					$this->error = 'subscription.missing';
				}
			} else {
				$this->error = 'customer.missing';
			}
		} else {
			$this->error = 'subscription.missing';
		}
		return false;
	}



	public function update($email, $id, $amount)
	{
		// get cached subscriptions
		$subscriptions = $this->subscriptions($email, true);
		if(isset($subscriptions[$id])) {
			if($customer = $this->client->customers->get($subscriptions[$id]['customer'])) {
				if($amount = $this->amount($amount)) {
					if($amount <= 1000) {
						if($subscription = $customer->getSubscription($subscriptions[$id]['subscription'])) {
							$subscription->amount =  (object) [
								'currency' => 'USD',
								'value' => $amount,
							];
							if($updated = $subscription->update()) {
								return true;
							} else {
								$this->error = 'subscription.error';
							}
						} else {
							$this->error = 'subscription.missing';
						}
					} else {
						$this->error = 'amount.maximum';
					}
				} else {
					$this->error = 'amount.invalid';
				}
			} else {
				$this->error = 'customer.missing';
			}
		} else {
			$this->error = 'subscription.missing';
		}
		return false;
	}


	protected function amount($amount) {
		// remove everything but numbers, ',' and '.'
		$clean = preg_replace('#[^0-9\,\.]#', '', $amount);
		if(strlen($clean) == 0) {
			return false;
		}
		// split on commas and dots
		$parts = preg_split('#[\,\.]+#', $clean);


		$first = array_shift($parts);
		$last = array_pop($parts);
		// if the first part is smaller or equal to three, it could be a thousands separator
		$thousands = strlen($first) <= 3;
		// Check the remaining parts, if any part is not trhee, it's not a thousand separator
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


		// return it with decimal dot
		return  number_format($amount, 2, '.', '');
	}


	public function customer($email)
	{
		$data = [];
		foreach($this->customers($email) as $customerId) {
			$customer = $this->client->customers->get($customerId);
			$metadata = $customer->metadata;
			if(is_string($metadata)) {
				$metadata = json_decode($metadata, true);
			} elseif(is_object($metadata)) {
				$metadata = (array) $metadata;
			} else {
				$metadata = [];
			}
			if(is_array($metadata)) {
				$data = array_merge($data, $metadata);
			}
		}
		return $data;
	}


	protected function customers($email)
	{
		$rows = $this->data->database()->select('customers')
		->from('mollie')
		->where('email', $this->emailKey($email))
		->result();

		if(count($rows) > 0) {
			$customers = json_decode($this->encrypt->decrypt($rows[0]['customers']), true);
			if(! is_array($customers)) {
				$customers = [];
			}

		} else {
			$customers = [];
		}
		return $customers;
	}



	public function import()
	{

		// Get array with ['email@email.nl' => ['cst_', 'cst_'] ]
		$customers = [];
		$parse = function($items) use (& $customers){
			foreach($items as $item) {
				$email = $this->normalizeEmail($item->email);
				if(! isset($customers[$email])) {
					$customers[$email] = [];
				}
				$customers[$email][] = $item->id;
			}
		};
		$items = $this->client->customers->page();
		$parse($items);

		while($items->hasNext()) {
			$items = $items->next();
			$parse($items);
		}

		$db = $this->data->database();
		// Remove email and store again
		foreach($customers as $email => $ids) {
			$emailKey = $this->emailKey($email);

			$db->delete('mollie')
			->where('email', $emailKey)
			->execute();

			$db->insert('mollie')
			->values([
				'email' => $emailKey,
				'customers' => $this->encrypt->encrypt(json_encode($ids))
			])
			->execute();
		}
	}


	public function addCustomer($email, $customerId)
	{
		$db = $this->data->database();
		$emailKey = $this->emailKey($email);

		$rows = $db->select('id', 'customers')
		->from('mollie')
		->where('email', $emailKey)
		->result();

		if(count($rows) > 0) {
			$customerIds = json_decode($this->encrypt->decrypt($rows[0]['customers']), true);
			if(! is_array($customerIds)) {
				$customerIds = [];
			}
			if(! in_array($customerId, $customerIds)) {
				$customerIds[] = $customerId;
			}
			$db->update('mollie')
			->set([
				'customers' => $this->encrypt->encrypt(json_encode($customerIds))
			])
			->where('id', $rows[0]['id'])
			->execute();
		} else {
			$db->insert('mollie')
			->values([
				'email' =>$emailKey,
				'customers' => $this->encrypt->encrypt(json_encode([$customerId]))
			])
			->execute();
		}
	}


	protected function emailKey($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// obfusciate email
			return $this->encrypt->obfusciate(
				$this->normalizeEmail($email)
			);
		} else {
			// this is no email, just return it
			return $email;
		}
	}

	protected function normalizeEmail($email)
	{
		return trim(strtolower($email));
	}

	protected function token()
	{
		$bytes = openssl_random_pseudo_bytes(128);
		$token = substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, 64);
		return $token;
	}
}