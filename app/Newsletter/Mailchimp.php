<?php
namespace Newsletter;

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


	public function get($email)
	{
		$result = $this->client->get('lists/' . $this->list . '/members/' . $this->hash($email));
		if(is_array($result) && isset($result['id'])) {
			return $result;
		} else {
			return false;
		}
	}


	public function update($email, $data = [], $tags = null, $status = null)
	{
		if(is_array($tags)) {
			$data['tags'] = $tags;
		}
		$payload = [
			  'merge_fields' => $data,
		];
		if($status !== null) {
			$payload['status'] = $status;
		}
		$result = $this->client->patch('lists/' . $this->list . '/members/' . $this->hash($email), $payload);

		if(is_array($result) && isset($result['id'])) {
			return $result;
		} else {
			return false;
		}
	}


	public function insert($email, $data = [], $status, $tags = null)
	{
		$data = [
			'email_address' => $email,
			'status' => $status,
			'merge_fields' => (object) $data,
		];

		if(is_array($tags)) {
			$data['tags'] = $tags;
		}

		$result = $this->get($email);
		if (is_array($result) && isset($result['id'])) {
			if ($result['status'] == 'unsubscribed') {
				return $this->client->patch('lists/' . $this->list . '/members/'  . $this->hash($email), $data);
			}
		} else {
			$post = $this->client->post('lists/' . $this->list . '/members/', $data);
			if (is_array($post) && isset($post['id'])) {
				return $post;
			} else {
				return false;
			}
		}
	}

	public function previewUrl($type)
	{
		/**
		 * Check if it's a regular MT campaign or MT+The Bell
		 *  MT+Bell newsketter does not have a List ID vs MT has List ID for
		 *   the English and Russian service
		**/
		$params = $type == 'bell' ? 'campaigns?sort_field=send_time&sort_dir=desc' : 'campaigns?list_id=' . $this->list . '&sort_field=send_time&sort_dir=desc';
		$campaigns = $this->client->get($params);
		if (is_array($campaigns) && isset($campaigns['campaigns']) && is_array($campaigns['campaigns']) && count($campaigns['campaigns']) > 0) {
			$campaigns = $campaigns['campaigns'];
			return $campaigns[0]['long_archive_url'];
		}
	}

	protected function hash($email)
	{
		return md5(strtolower($email));
	}

}
