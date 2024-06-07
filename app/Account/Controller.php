<?php
namespace Account;

use Sulfur\View;
use Sulfur\Request;
use Sulfur\Response;
use Url;



class Controller
{



	/*
	public function clarify(Encrypt $enrypt, $email = '')
	{
		$data = [
		];
		foreach($data as $row) {
			echo $enrypt->clarify($row[0]) . "\t" . ($row[1] ? 'yes' : 'no') . "\n";
		}
		//echo $enrypt->clarify($email);
	}
	*/
	/*
	public function obfusciate(Encrypt $enrypt, $email)
	{
		echo $enrypt->obfusciate($email);
	}

	public function decrypt(Encrypt $enrypt, $data)
	{
		echo $enrypt->decrypt($data);
	}
	*/

	public function register(Request $request, View $view)
	{
		return $view->render('account/register',[
			'email' => $request->query('email')
		]);
	}

	public function signin(View $view)
	{
		return $view->render('account/signin');
	}

	public function signout(Identity $identity, Response $response, Url $url)
	{
		$identity->destroy($response);
		$response->redirect($url->route('signin'));
	}


	public function confirmation(Identity $identity, View $view)
	{
		return $view->render('account/confirmation',[
			'authenticated' => $identity->confirmed()
		]);
	}

	public function confirm($token, View $view)
	{
		return $view->render('account/confirm', ['token' => $token]);
	}

	public function recover(View $view)
	{
		return $view->render('account/recover');
	}

	public function reset($token, View $view)
	{
		return $view->render('account/reset', ['token' => $token]);
	}

	public function dashboard(View $view, Mollie $mollie)
	{
		return $view->render('account/dashboard');
	}

	public function terms(Response $response)
	{
		$response->redirect('https://www.themoscowtimes.com/page/privacy-policy');
	}
}