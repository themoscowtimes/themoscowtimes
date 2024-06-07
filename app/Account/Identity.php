<?php

namespace Account;

use Sulfur\Request;
use Sulfur\Session;


class Identity
{
	protected $request;
	protected $session;
	protected $model;
	protected $jwt;
	protected $pbk;

	protected $account = null;
	protected $confirmed = null;
	protected $error = '';


	public function __construct(
		Request $request,
		Session $session,
		Model $model,
		Jwt $jwt, Pbk
		$pbk
	)
	{
		$this->request = $request;
		$this->session = $session;
		$this->model = $model;
		$this->jwt = $jwt;
		$this->pbk = $pbk;
	}


	public function error($error = null)
	{
		if($error === null) {
			return $this->error;
		} else {
			$this->error = $error;
		}
	}


	public function credentials($email, $password, $agent = [])
	{

		if(! $email) {
			$this->error('email.missing');
			return false;
		}

		if(! $password) {
			$this->error('password.missing');
			return false;
		}

		// check credentials
		if($account = $this->model->byEmail($email)) {
			
			if($this->model->throttled($account)) {
				$this->error('identity.throttle');
				return false;
			}

			if(password_verify($password, $account->password)){
				// password checks out
				$this->confirmed = true;
				// Persist uid
				$this->persist($account);
				// Unlock ansd persist pbk
				$this->pbk->set($account->pbk, $password);
				// Add ip / Check for new ip
				$this->model->agent($account, $agent);
				// We're good
				return true;
			} else {
				$this->model->throttle($account);
				$this->error('identity.credentials');
				return false;
			}
		} else {
			$this->error('identity.unknown');
			return false;
		}
	}

	/*
	public function facebook($facebook, $email)
	{
		if($account = $this->model->byFacebook($facebook, $email)){
			$this->confirmed = true;
			$this->persist($account);
			return true;
		} else {
			$this->error('facebook.unknown');
			return false;
		}
	}
	*/



	/**
	 * Check if identity is confirmed
	 * Will proceed to do confirmation if unknown
	 */
	public function confirmed()
	{
		return $this->confirm();
	}


	/**
	 * Confirm identity. Check in order:
	 * - Jwt in the header
	 * - Permanent cookie
	 * - uid in Session
	 *
	 * Will go through these only once. Will return the first reults in subsequent calls
	 */
	public function confirm()
	{
		if($this->confirmed === null) {

			// Try to confirm by Bearer token
			if($token = $this->jwt->header($this->request->header('Authorization'), null)) {
				if($uid = $this->jwt->claim($token, 'uid')) {
					if($account = $this->model->byUid($uid)){
						$this->confirmed = true;
						$this->persist($account);
						return true;
					}
				}
			}

			// Try to confirm by cookie
			if($token = $this->request->cookie('__permanent', null)) {
				if($uid = $this->jwt->claim($token, 'uid')) {
					if($account = $this->model->byPermanent($uid)){
						$this->confirmed = true;
						$this->persist($account);
						return true;
					}
				}
			}

			// Try to confirm by Session
			if($uid = $this->session->get('uid', null)) {
				if($account = $this->model->byUid($uid)){
					$this->confirmed = true;
					$this->persist($account);
					return true;
				}
			}

			// Conclusion: not confirmed
			$this->confirmed = false;
		}

		return $this->confirmed;
	}


	/**
	 * Get current account
	 * Or set account
	 * @param Account\Entity $account
	 * @return void
	 */
	public function account($account = null)
	{
		if($account === null) {
			return $this->account;
		} else {
			// set account
			$this->confirmed = true;
			$this->persist($account);
		}
	}


	/**
	 * Set the account
	 * Persist the uid in the session
	 * @param Account\Entity $account
	 */
	protected function persist($account)
	{
		if($account) {
			// Store account uid in session
			$this->session->set('uid', $account->uid);

			// set current account
			$this->account = $account;
		}
	}


	/**
	 * Make sure identity is not confirmed
	 * @param Sulfur\Response $response
	 * @param boolean $all
	 */
	public function destroy($response, $all = false)
	{
		if($all && $this->account) {
			// logout on all devices by creating fresh tokens
			$this->model->renew($this->account);
		}

		// remove account
		$this->account = null;

		// identity not confirmed
		$this->confirmed = false;

		// remove permanent cookie
		$response->cookie('__permanent', '', 0);

		// destroy pbk
		$this->pbk->destroy();

		// destroy session
		$this->session->destroy();
	}


	/**
	 * Set a cookie for permanent login
	 * @param Sulfur\Response $response
	 */
	public function cookie($response)
	{
		if($this->account) {
			$response->cookie('__permanent', $this->jwt->create(['uid' => $this->account->permanent], 10 * 365 * 24 * 3600), time() + 10 * 365 * 24 * 3600);
		}
	}


	/**
	 * Create a bearer token that can be used by an app
	 * @return string
	 */
	public function bearer()
	{
		if($this->account) {
			return $this->jwt->create(['uid' => $this->account->uid], 10 * 365 * 24 * 3600);
		}
	}
}