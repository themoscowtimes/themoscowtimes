<?php
namespace Account;

use Sulfur\Data;
use Message\Email as Message;


class Model
{
	protected $data;
	protected $encrypt;
	protected $pbk;
	protected $jwt;
	protected $message;

	// Error message
	protected $error = '';

	// Public properties
	protected $public = [];

	// Protected properties
	protected $protected = ['country', 'name', 'firstname', 'lastname', 'phone_country', 'phone_number', 'birthdate', 'sector'];

	// Private properties
	protected $private = [];


	public function __construct(
		Data $data,
		Encrypt $encrypt,
		Pbk $pbk,
		Jwt $jwt,
		Message $message,
		Mailchimp $mailchimp
	)
	{
		$this->data = $data;
		$this->encrypt = $encrypt;
		$this->pbk = $pbk;
		$this->jwt = $jwt;
		$this->message = $message;
		$this->mailchimp = $mailchimp;
	}


	public function error($error = null)
	{
		if($error === null) {
			return $this->error;
		} else {
			$this->error = $error;
		}
	}


	public function byEmail($email)
	{
		if($email) {
			return $this->data->finder('Account\Entity')
			->where('email', $this->encrypt->obfusciate($this->normalizeEmail($email)))
			->where('email', '<>', '')
			->where('email', '<>', null)
			->one();
		}
	}


	public function byUid($uid)
	{
		if($uid) {
			return $this->data->finder('Account\Entity')
			->where('uid', $uid)
			->where('uid', '<>', '')
			->where('uid', '<>', null)
			->one();
		}
	}


	public function byConfirm($uid)
	{
		if($uid) {
			return $this->data->finder('Account\Entity')
			->where('confirm', $uid)
			->where('confirm', '<>', '')
			->where('confirm', '<>', null)
			->one();
		}
	}


	public function byPermanent($uid)
	{
		if($uid) {
			return $this->data->finder('Account\Entity')
			->where('permanent', $uid)
			->where('permanent', '<>', '')
			->where('permanent', '<>', null)
			->one();
		}
	}


	public function byReset($uid)
	{
		if($uid) {
			return $this->data->finder('Account\Entity')
			->where('reset', $uid)
			->where('reset', '<>', '')
			->where('reset', '<>', null)
			->one();
		}
	}


	/**
	 * Renew all the uids of an account to signout of all devices
	 * @param Account\Entity $account
	 */
	public function renew($account)
	{
		$account->uid = $this->token();
		$account->confirm = $account->confirm == '' ? '' : $this->token();
		$account->permanent = $this->token();
		$account->reset = '';
		$this->data->save($account);
	}


	/**
	 * Check if an account was confirmed
	 * @param Account\Entity $account
	 * @return boolean
	 */
	public function confirmed($account)
	{
		if($account->confirmed && strtotime($account->confirmed)) {
			return true;
		}
	}


	/**
	 * Check if an account was throttled
	 * @param Account\Entity $account
	 * @return boolean
	 */
	public function throttled($account)
	{
		$failed = $account->failed;
		if(! is_array($failed)) {
			$failed = [];
		}
		$attempts = 5;
		$time = 5 * 60;
		if(count($failed) >= $attempts) {
			$oldest = $failed[count($failed) - $attempts];
			if($oldest > time() - $time) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Throttle a failed signin attempt
	 * @param Account\Entity $account
	 */
	public function throttle($account)
	{
		$failed = $account->failed;
		if(! is_array($failed)) {
			$failed = [];
		}

		$failed[] = time();
		$account->failed = $failed;
		$this->data->save($account);
	}


	/**
	 * Store new agents with account
	 * @param Account\Entity $account
	 * @param array $agent
	 */
	public function agent($account, $agent)
	{
		$agents = $account->agents;

		if(! is_array($agents)) {
			$agents = [];
		}
		$new = true;
		$newIp = true;
		foreach($agents as $time => $a) {
			if($a[0] == $agent[0]) {
				$newIp = false;
			}
			if($a[0] == $agent[0] &&  $a[1] == $agent[1]) {
				$new = false;
				break;
			}
		}

		if($new) {
			// New ip / browser combination
			$agents[date('Y-m-d H:i:s')] = $agent;
			$account->agents = $agents;
			$this->data->save($account);
		}

		if($newIp) {
			// New ip: send out warning
			$this->message->ip(
				$this->encrypt->clarify($account->email),
				$agent[0]
			);
		}
	}


	/**
	 * Create an account
	 * @param array $values
	 * @return boolean|Account\Entity
	 */
	public function create($values, $agent)
	{
		// check if agrees
		if(! isset($values['agreed']) || $values['agreed'] != 1) {
			$this->error('terms.rejected');
			return false;
		}

		// check email
		if(! isset($values['email'])) {
			$this->error('email.missing');
			return false;
		}

		// check email
		if (! filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error('email.invalid');
			return false;
		}

		// Create encrypted mail
		$email = $this->encrypt->obfusciate($this->normalizeEmail($values['email']));

		// Check existing
		if(
			$this->data->finder('Account\Entity')
			->where('email', $email)
			->count() > 0
		) {
			$this->error('email.exists');
			return false;
		}

		// Check password / facebook
		if(! isset($values['facebook']) || ! $values['facebook']) {
			if(! isset($values['password'])) {
				$this->error('password.missing');
				return false;
			}
			if(! $this->strong($values['password'])) {
				$this->error('password.weak');
				return false;
			}
		}

		// Create account
		$account = new Entity();
		$account->uid = $this->token();
		$account->permanent = $this->token();
		$account->pbk = $this->encrypt->pbk($values['password']);
		$account->created = date('Y-m-d H:i:s',time());
		$account->agreed = date('Y-m-d H:i:s',time());
		$account->email = $email;
		$account->agents = [date('Y-m-d H:i:s') => $agent];

		// set facebook or password
		$account->password = password_hash($values['password'], PASSWORD_DEFAULT);

		// persist
		$this->data->save($account);

		// send confirmation email
		$this->confirmation($account);

		// Update mailchimp
		$this->mailchimp->create($values['email']);

		// return created account
		return $account;
	}


	/**
	 * Send a confirmation email
	 * @param Account\Entity $account
	 * @return Account\Entity
	 */
	public function confirmation($account)
	{
		if(! $account->confirm) {
			// create token
			$account->confirm = $this->token();
			$this->data->save($account);
		}
		// send message
		$this->message->confirmation(
			$this->encrypt->clarify($account->email),
			$this->jwt->create(['uid' => $account->confirm], 2 * 24 * 3600)
		);
		return $account;
	}


	/**
	 * Confirm an account
	 * @param string $token
	 * @return boolean|Account\Entity
	 */
	public function confirm($token, $data)
	{
		// Check data
		foreach(['firstname', 'lastname', 'phone_country', 'phone_number'] as $key) {
			if(! isset($data[$key]) || ! $data[$key] ) {
				$this->error($key . '.missing');
				return false;
			}
		}

		if($uid = $this->jwt->claim($token, 'uid')) {
			if($account = $this->byConfirm($uid)) {
				// remove confirm token
				$account->confirm = '';
				// set confirmed date
				$account->confirmed = date('Y-m-d H:i:s');
				// persist
				$this->data->save($account);
				// update data
				$this->update($account->uid, $data);
				// Update mailchimp
				$this->mailchimp->confirm($this->encrypt->clarify($account->email));
				// done
				return $account;
			} else {
				$this->error('confirm.unknown');
				return false;
			}
		} else {
			$this->error('confirm.invalid');
			return false;
		}
	}


	/**
	 * Recover password
	 * @param string $email
	 * @return boolean|Account\Entity
	 */
	public function recover($email)
	{
		if($account = $this->byEmail($email)) {
			// create reset uid
			$account->reset = $this->token();
			$this->data->save($account);

			// create token
			$token = $this->jwt->create(['uid' => $account->reset], 24 * 3600);

			// send message
			$this->message->recover(
				$this->encrypt->clarify($account->email),
				$token
			);
			return $account;
		} else {
			$this->error('recover.unknown');
			return false;
		}
	}


	/**
	 * Reset password
	 * @param string $token
	 * @param string $password
	 * @return boolean|Account\Entity
	 */
	public function reset($token, $password)
	{
		if($uid = $this->jwt->claim($token, 'uid')) {
			if($account = $this->byReset($uid)) {
				if(! $this->strong($password)) {
					$this->error('password.weak');
					return false;
				}
				// reset passowrd
				$account->password = password_hash($password, PASSWORD_DEFAULT);

				// TODO: pbk needs to be unpacked with old password and rebuilt
				// right now, the private data is unaccessible
				$account->pbk = $this->encrypt->pbk($password);

				// remove reset token
				$account->reset = '';

				// save
				$this->data->save($account);

				// done
				return $account;
			} else {
				$this->error('reset.unknown');
				return false;
			}
		} else {
			$this->error('reset.invalid');
			return false;
		}
	}


	/**
	 * Update current account
	 * @param type $values
	 * @return type
	 */
	public function update($uid, $values)
	{
		$account = $this->byUid($uid);

		if($account) {
			// Update email

			/*
			// Dont update e-mail, to keep Data integrety
			if(isset($values['email'])) {
				// check email
				if (! filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
					$this->error('email.invalid');
					return false;
				}

				$email = $this->encrypt->obfusciate($this->normalizeEmail($values['email']));

				// check existing
				if(
					$this->data->finder('Account\Entity')
					->where('email', $email)
					->where('id', '<>', $account->id)
					->count() > 0
				) {
					$this->error('email.exists');
					return false;
				}

				$account->email = $email;
			}
			*/

			// Update password
			if(isset($values['password'])) {
				if(! $this->strong($values['password'])) {
					$this->error('password.weak');
					return false;
				}
				$account->password = password_hash($values['password'], PASSWORD_DEFAULT);

				// TODO: pbk needs to be unpacked with old password and rebuilt
				// right now, the private data is unaccessible
				$account->pbk = $this->encrypt->pbk($values['password']);
			}

			// Update public values
			$public = [];
			foreach($this->public as $key) {
				if(isset($values[$key])) {
					$public[$key] = $values[$key];
				}
			}
			if(count($public) > 0) {
				$this->setPublic($account, $public);
			}


			// Update protected values
			$protected = [];

			foreach($this->protected as $key) {
				if(isset($values[$key])) {
					$protected[$key] = $values[$key];
				}
			}
			if(count($protected) > 0) {
				$this->setProtected($account, $protected);
			}



			// Update private values
			$private = [];
			foreach($this->private as $key) {
				if(isset($values[$key])) {
					$private[$key] = $values[$key];
				}
			}
			if(count($private) > 0) {
				$this->setPrivate($account, $private);
			}
			$this->data->save($account);

			// send public and protected to mailchimp
			$this->mailchimp->update($this->encrypt->clarify($account->email), array_merge($public, $protected));

			return true;
		} else {
			$this->error('account.unknown');
			return false;
		}
	}


	public function email($account)
	{
		if($account) {
			return $this->encrypt->clarify($account->email);
		}
	}

	public function getPublic($account, $key = null)
	{
		return $this->getData($account, $key, 'public');
	}

	public function getProtected($account, $key = null)
	{
		return $this->getData($account, $key, 'protected');
	}

	public function getPrivate($account, $key = null)
	{
		return $this->getData($account, $key, 'private');
	}

	protected function getData($account, $key = null, $type = 'public')
	{
		if($account) {
			if($type === 'public') {
				$public = $account->public;
				if(! is_array($public)) {
					$public = [];
				}
				if($key === null) {
					return $public;
				} elseif(isset($public[$key])) {
					return $public[$key];
				}
			}
			if($type === 'protected') {
				if(! $account->__protected) {
					$protected = json_decode($this->encrypt->decrypt($account->protected), true);
					if(! is_array($protected)) {
						$protected = [];
					}
					$account->__protected = $protected;
				}
				if($key === null) {
					return $account->__protected;
				} elseif(isset($account->__protected[$key])) {
					return $account->__protected[$key];
				}
			}
			if($type === 'private' && $pbk = $this->pbk->get()) {
				if(! $account->__private) {
					$private = json_decode($this->encrypt->decrypt($account->private, $pbk), true);
					if(! is_array($private)) {
						$private = [];
					}
					$account->__private = $private;
				}
				if($key === null) {
					return $account->__private;
				} elseif(isset($account->__private[$key])) {
					return $account->__private[$key];
				}
			}
		}
	}


	public function setPublic($account, $keyOrValues, $value = null)
	{
		return $this->setData($account, $keyOrValues, $value, 'public');
	}

	public function setProtected($account, $keyOrValues, $valueOrReplace = null)
	{
		return $this->setData($account, $keyOrValues, $valueOrReplace, 'protected');
	}

	public function setPrivate($account, $keyOrValues, $valueOrReplace = null)
	{
		return $this->setData($account, $keyOrValues, $valueOrReplace, 'private');
	}


	protected function setData($account, $keyOrValues, $valueOrReplace = null, $type = 'public')
	{
		if(is_array($keyOrValues)) {
			$values = $keyOrValues;
			$replace = $valueOrReplace === true;
		} elseif(is_string($keyOrValues)) {
			$values = [$keyOrValues => $valueOrReplace];
			$replace = false;
		} else {
			return;
		}

		if($account) {
			if($type === 'public') {
				$public = $account->public;
				if(! is_array($public)) {
					$public = [];
				}

				$account->public =	$replace ? $values :  array_merge($public, $values);
			} elseif($type === 'protected') {
				$protected = json_decode($this->encrypt->decrypt($account->protected), true);
				if(! is_array($protected)) {
					$protected = [];
				}
				$account->protected = $this->encrypt->encrypt(json_encode($replace ? $values :  array_merge($protected, $values)));
			} elseif($type === 'private' && $pbk = $this->pbk->get()) {

				$private = json_decode($this->encrypt->decrypt($account->private), $pbk);
				if(! is_array($private)) {
					$private = [];
				}
				$account->private = $this->encrypt->encrypt(json_encode($replace ? $values :  array_merge($private, $values)), $pbk);
			}
			$this->data->save($account);
		}
	}




	/**
	 * Check password strength
	 * @param type $password
	 * @return boolean
	 */
	protected function strong($password)
	{
		$uppercase = preg_match('#[A-Z]#', $password);
		$lowercase = preg_match('#[a-z]#', $password);
		$number = preg_match('#[0-9]#', $password);
		$special = preg_match('#[^0-9a-zA-Z]#', $password);

		if(
			! $uppercase
			|| ! $lowercase
			|| ! $number
			|| ! $special
			|| strlen($password) < 8
		){
			return false;
		}
		return true;
	}


	/**
	 * Normalize e-mail
	 * @return type
	 */
	protected function normalizeEmail($email)
	{
		return trim(strtolower($email));
	}


	/**
	 * Generate a random 64 char string
	 * @return string
	 */
	protected function token()
	{
		$bytes = openssl_random_pseudo_bytes(128);
		$token = substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, 64);
		return $token;
	}
}