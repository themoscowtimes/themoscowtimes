<?php

namespace Contribute;

use Sulfur\Session;
use Sulfur\Request;
use Sulfur\Response;
use Sulfur\View;
use Sulfur\Form;
use Sulfur\Url;

class Controller
{

	public function view(View $view, Form $form)
	{
		return $view->render('contribute/index', ['form' => $form->get('Contribute\Form')]);
	}

	public function submit(Request $request, Mollie $mollie, Url $url)
	{
		if($url = $mollie->create(
			$request->post(),
			$url->route('mollie_returned'),
			$url->route('mollie_webhook')
		)) {
			$repsonse = [
				'success' => true,
				'url' => $url
			];
		} else {
			$repsonse = [
				'success' => false,
				'errors' => $mollie->errors()
			];
		}
		return json_encode($repsonse);
	}


	public function returned(Session $session, Response $response, Mollie $mollie, View $view, Url $url)
	{
		// Get payment id from session before it's gone
		$paymentId = $session->get('mollie_payment_id', false);
		if($mollie->success()) {
			$info = $mollie->info($paymentId);
			if($info['recurring']) {
				if($info['account']) {
					return $view->render('account/signin', [
						'referer' => 'contribution',
						'email' => $info['email']
					]);
				} else {
					return $view->render('account/register', [
						'referer' => 'contribution',
						'email' => $info['email']
					]);
				}
			} else {
				return $view->render('contribute/done', ['success' => true]);
			}
		} else {
			return $view->render('contribute/done', ['success' => false]);
		}
	}


	public function webhook(Request $request, Mollie $mollie, Url $url)
	{
		$id = $request->post('id');
		$mollie->process($id, $url->route('mollie_recurringwebhook'));
	}


	public function recurringwebhook(Request $request, Mollie $mollie)
	{
		$id = $request->post('id');
		$mollie->recurring($id);
	}
}