<?php
namespace Account;

use Sulfur\Response;
use Sulfur\Csrf;

use Api as Helper;
use Url;

//use Facebook;
use Lang;


class Api
{
/*
	public function identity(Helper $api, Response $response, Identity $identity, Model $model)
	{
		if($identity->confirmed()) {
			$api->resonde($response, [
				'authenticated' => true,
				'confirmed' => $model->confirmed($identity->account()),
				'type' => 'default'
			]);
		} else {
			// failed
			$api->fail($response, 'unknown', [
				'authenticated' => false,
				'confirmed' => false,
				'type' => 'default'
			]);
		}
	}
*/

	public function signin(Helper $api, Response $response, Identity $identity, Model $model, Lang $lang)
	{
		// signout
		$identity->destroy($response);

		if($identity->credentials($api->post('identity'), $api->post('credentials'), $api->agent())) {
			// signin succesful
			/*
			if($api->post('permanent') == 1) {
				// set permanent cookie
				$identity->cookie($response);
			}
			 */
			// return bearer token
			$api->respond($response, ['success' => true, 'token' => $identity->bearer()]);
		} else {
			// failed
			$error = $identity->error();
			$api->respond($response, ['success' => false, 'message' => $lang->account($error, $error)]);
		}
	}

/*
	public function facebook(Helper $api, Response $response, Identity $identity, Facebook $facebook, Model $model)
	{
		// signout
		$identity->destroy($response);
		if($info = $facebook->info($api->post('token'))){
			// facebook info found
			if($identity->facebook($info->id, $info->email)) {
				// facebook signin succesful
				// return bearer token
				$api->success($response, ['token' => $identity->bearer()]);
			} else {
				// failed facebook signin
				$api->fail($response, $identity->error(), [
					'facebook' => $info->id,
					'email' => $info->email,
				]);
			}
		} else {
			// failed facebook
			$api->fail($response, $facebook->error());
		}
	}
*/

	public function signout(Helper $api, Response $response, Identity $identity)
	{
		// signout
		$identity->destroy($response, $api->post('all') == 1);
		// done
		$api->respond($response, ['success' => true]);
	}


	public function register(Helper $api, Response $response, Identity $identity, Model $model, Lang $lang)
	{
		// signout
		$identity->destroy($response);
		// Create account
		if($account = $model->create($api->post(), $api->agent())) {
			// account created
			// signin programatically
			$identity->account($account);
			// return bearer token
			$api->respond($response, ['success' => true, 'token' => $identity->bearer()]);
		} else {
			// failed creation
			$error = $model->error();
			$api->respond($response, ['success' => false, 'message' => $lang->account($error, $error)]);
		}
	}


	public function confirmation(Helper $api, Response $response, Identity $identity, Model $model, Lang $lang)
	{
		if($identity->confirmed()) {
			// resend account confirmation
			$model->confirmation($identity->account());
			// success
			$api->respond($response,  ['success' => true]);
		} else {
			$error = 'identity.unknown';
			$api->respond($response,  ['success' => false, 'message' => $lang->account($error, $error)]);
		}
	}


	public function customer(Helper $api, Response $response, Jwt $jwt, Model $model, Mollie $mollie, Lang $lang)
	{
		$data = [
			'firstname' => '',
			'lastname' => '',
			'phone' => '',
		];
		if($uid = $jwt->claim($api->post('token'), 'uid')) {
			if($account = $model->byConfirm($uid)) {
				$customer = $mollie->customer($account->email);
				foreach(array_keys($data) as $key) {
					if(isset($customer[$key])) {
						$data[$key] = $customer[$key];
					}
				}
			}
		}
		$api->respond($response,  $data);
	}



	public function confirm(Helper $api, Response $response, Identity $identity, Model $model, Csrf $csrf, Lang $lang)
	{
		if( ! $csrf->validate($api->post('csrf'))) {
			$api->respond($response, ['success' => false, 'message' => $lang->account('csrf.invalid', 'csrf.invalid')]);
			return;
		}

		if($account = $model->confirm($api->post('token'), $api->post())) {
			// sign in programatically
			$identity->account($account);
			// success
			$api->respond($response,  ['success' => true]);
		} else {
			// fail
			$error = $model->error();
			$api->respond($response, ['success' => false, 'message' => $lang->account($error, $error)]);
		}
	}


	public function recover(Helper $api, Response $response, Model $model, Lang $lang)
	{
		if($model->recover($api->post('email'))) {
			// success
			$api->respond($response,  ['success' => true]);
		} else {
			// fail
			$error = $model->error();
			$api->respond($response, ['success' => false, 'message' => $lang->account($error, $error)]);
		}
	}



	public function reset(Helper $api, Response $response, Model $model, Csrf $csrf, Lang $lang)
	{
		if( ! $csrf->validate($api->post('csrf'))) {
			$api->respond($response, ['success' => false, 'message' => $lang->account('csrf.invalid', 'csrf.invalid')]);
			return;
		}

		if($account = $model->reset($api->post('token'), $api->post('password'))) {
			// success
			$api->respond($response,  ['success' => true]);
		} else {
			// fail
			$error = $model->error();
			$api->respond($response, ['success' => false, 'message' => $lang->account($error, $error)]);
		}
	}


	public function account(
		Helper $api,
		Response $response,
		Identity $identity,
		Model $model,
		Lang $lang
	)
	{
		if($account = $identity->account()) {
			$email = $model->email($account);
			$api->respond($response, [
				'name' => explode('@', $email)[0] ?? $email,
				'email' => $email,
				'firstname' => $model->getProtected($account, 'firstname'),
				'lastname' => $model->getProtected($account, 'lastname'),
				'phone_country' => $model->getProtected($account, 'phone_country'),
				'phone_number' => $model->getProtected($account, 'phone_number'),
				'sector' => $model->getProtected($account, 'sector'),
				'birthdate' => $model->getProtected($account, 'birthdate'),
			]);
		} else {
			// fail
			$error = $model->error();
			$api->respond($response, ['success' => false, 'message' => $lang->account('identity.unconfirmed', 'identity.unconfirmed')]);
		}
	}



	public function donations(
		Helper $api,
		Response $response,
		Identity $identity,
		Mollie $mollie,
		Url $url,
		Lang $lang
	)
	{
		if($account = $identity->account()) {
			$donations = [];
			foreach($mollie->subscriptions($account->email) as $id => $subscription) {
				if($subscription['type'] == 'donation') {
					$subscription['cancel'] =  $url->route('api_subscriptioncancel', ['id' => $id]);
					$subscription['update'] =  $url->route('api_subscriptionupdate', ['id' => $id]);
					$donations[] = $subscription;
				}
			}
			$api->respond($response, $donations);
		} else {
			$api->respond($response, ['success' => false, 'message' => $lang->account('identity.unconfirmed', 'identity.unconfirmed')]);
		}
	}



	public function subscriptioncancel(
		Helper $api,
		Response $response,
		Identity $identity,
		Mollie $mollie,
		Lang $lang,
		Csrf $csrf,
		$id
	)
	{
		if( ! $csrf->validate($api->post('csrf'))) {
			$api->respond($response, ['success' => false, 'message' => $lang->account('csrf.invalid', 'csrf.invalid')]);
			return;
		}

		if($account = $identity->account()) {
			if($mollie->cancel($account->email, $id)) {
				$api->respond($response,  ['success' => true]);
			} else {
				$api->respond($response, ['success' => false, 'message' => $lang->account($mollie->error(), $mollie->error())]);
			}
		} else {
			$api->respond($response, ['success' => false, 'message' => $lang->account('identity.unconfirmed', 'identity.unconfirmed')]);
		}
	}


	public function subscriptionupdate(
		Helper $api,
		Response $response,
		Identity $identity,
		Mollie $mollie,
		Lang $lang,
		Csrf $csrf,
		$id
	)
	{
		if( ! $csrf->validate($api->post('csrf'))) {
			$api->respond($response, ['success' => false, 'message' => $lang->account('csrf.invalid', 'csrf.invalid')]);
			return;
		}

		if($account = $identity->account()) {
			if($mollie->update($account->email, $id, $api->post('amount'))) {
				$api->respond($response,  ['success' => true]);
			} else {
				$api->respond($response, ['success' => false, 'message' => $lang->account($mollie->error(), $mollie->error())]);
			}
		} else {
			$api->respond($response, ['success' => false, 'message' => $lang->account('identity.unconfirmed', 'identity.unconfirmed')]);
		}
	}


	public function update(
		Helper $api,
		Response $response,
		Identity $identity,
		Model $model,
		Lang $lang,
		Csrf $csrf
	)
	{
		if( ! $csrf->validate($api->post('csrf'))) {
			$api->respond($response, ['success' => false, 'message' => $lang->account('csrf.invalid', 'csrf.invalid')]);
			return;
		}

		if( ! $identity->confirmed()) {
			$api->respond($response, ['success' => false, 'message' => $lang->account('identity.unconfirmed', 'identity.unconfirmed')]);
			return;
		}

		if($model->update($identity->account()->uid, $api->post())) {
			// success
			$api->respond($response, ['success' => true, 'message' => $lang->account('account.updated', 'account.updated')]);
		} else {
			// fail
			$error = $model->error();
			$api->respond($response, ['success' => false, 'message' => $lang->account($error, $error)]);
		}
	}
}