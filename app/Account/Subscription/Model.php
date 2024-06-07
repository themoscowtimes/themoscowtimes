<?php
namespace Account\Subscription;

use Sulfur\Data;
use Message\Email as Message;
use Account\Identity;

class Model
{
	protected $identity;
	protected $data;
	protected $message;
	protected $error = '';

	public function __construct(Identity $identity, Data $data, Message $message)
	{
		$this->identity = $identity;
		$this->data = $data;
		$this->message = $message;
	}


	public function subscriptions()
	{
		if($account = $identity->account()) {
			return $account->subscriptions;
		}
		return [];
	}


	public function active()
	{
		if($account = $identity->account()) {
			return $account->subscriptions;
		}
		return [];
	}



}