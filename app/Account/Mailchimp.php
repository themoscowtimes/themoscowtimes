<?php
namespace Account;

use DrewM\MailChimp\MailChimp as Client;

class Mailchimp
{

	protected $client;
	protected $list;

	public function __construct(Client $client, $list)
	{
		$this->client = $client;
		$this->list = $list;

	}


	public function create($email)
	{
		// Create subscriber
		$this->client->post('lists/' . $this->list . '/members/', [
			'email_address' => $email,
			'status' => 'transactional',
		]);
		// Add tag
		$result = $this->client->post('lists/' . $this->list . '/members/' . $this->hash($email) . '/tags', [
			'tags' => [
				(object) [
					'name' => 'ACCOUNT CREATED',
					'status' => 'active'
				]
			]
		]);
	}


	public function update($email, $data)
	{

		$merge = [];
		if(isset($data['firstname']) && $data['firstname']) {
			$merge['FNAME'] = $data['firstname'];
		}
		if(isset($data['lastname']) && $data['lastname']) {
			$merge['LNAME'] = $data['lastname'];
		}
		/*
		if(isset($data['sector']) && $data['sector']) {
			$merge['OCCUPATION'] = $data['sector'];
		}
		 */
		if(isset($data['birthdate']) && $data['birthdate'] && strtotime($data['birthdate'])) {
			$merge['BIRTHDAY'] = date('m/d', strtotime($data['birthdate']));
		}

		if(isset($data['phone_number']) && $data['phone_number']) {
			if(isset($data['phone_country']) && $data['phone_country']) {
				$merge['PHONE'] = $data['phone_country'] . ' ' . $data['phone_number'];
			} else {
				$merge['PHONE'] = $data['phone_number'];
			}
		}
		$this->client->patch('lists/' . $this->list . '/members/' . $this->hash($email), [
			'merge_fields' => (object) $merge,
		]);
	}


	public function confirm($email)
	{
		/*
		$this->client->post('customer-journeys/journeys/1826/steps/11034/actions/trigger', [
			'email_address' => $email
		]);
		*/

		$result = $this->client->post('lists/' . $this->list . '/members/' . $this->hash($email) . '/tags', [
			'tags' => [
				(object) [
					'name' => 'ACCOUNT COFIRMED',
					'status' => 'active'
				]
			]
		]);


	}


	protected function hash($email)
	{
		return md5(strtolower($email));
	}

}