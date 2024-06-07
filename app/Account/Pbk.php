<?php

namespace Account;

use Sulfur\Session;

class Pbk
{
	protected $session;


	public function __construct(Encrypt $encrypt, Session $session)
	{
		$this->encrypt = $encrypt;
		$this->session = $session;
	}


	public function set($pbk, $password)
	{
		$this->session->set('pbk', 	$this->encrypt->pbk($pbk, $password));
	}

	
	public function get()
	{
		return $this->session->get('pbk');
	}


	public function destroy()
	{
		$this->session->set('pbk', '');
	}
}
