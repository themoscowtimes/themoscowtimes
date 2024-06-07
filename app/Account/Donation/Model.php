<?php
namespace Account\Donation;


use Sulfur\Data;
use Account\Encrypt;



class Model
{
	protected $data;
	protected $error = '';

	public function __construct(Data $data, Encrypt $encrypt, Mollie $mollie)
	{

		$this->data = $data;
		$this->encrypt = $encrypt;
		$this->mollie = $mollie;
	}


	public function donations()
	{
		if($account = $identity->account()) {
			$email = $this->acccount->email($account);




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