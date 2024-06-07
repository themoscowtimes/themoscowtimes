<?php

namespace Message;

use Sulfur\Email as BaseEmail;
use Sulfur\View;
use Url;

class Email
{

	protected $config = [
		'domain' => 'themoscowtimes.com',
	];

	public function __construct(
		BaseEmail $email,
		View $view,
		Url $url,
		$config = []
	)
	{
		$this->email = $email;
		$this->view = $view;
		$this->url = $url;
		$this->config = array_merge($this->config, $config);
	}


	public function confirmation($email, $token)
	{
		$body = $this->view->render('account/email/confirmation', [
			'url' => $this->url->route('confirm', ['token' => $token])
		]);

		$this->email->message(
			'Confirm your account on The Moscow Times',
			$email,
			'no-reply-accounts@' . $this->config['domain'],
			$body
		)->send();
	}


	public function recover($email, $token)
	{
		$body = $this->view->render('account/email/recover', [
			'url' => $this->url->route('reset', ['token' => $token])
		]);

		$this->email->message(
			'Reset your password for The Moscow Times account',
			$email,
			'no-reply-accounts@' . $this->config['domain'],
			$body
		)->send();
	}

	public function ip($email, $ip)
	{
		$body = $this->view->render('account/email/ip', [
			'ip' => $ip
		]);

		$this->email->message(
			'New login location for your account on The Moscow Times',
			$email,
			'no-reply-accounts@' . $this->config['domain'],
			$body
		)->send();
	}


	public function donation($email, $name, $amount, $currency = 'USD')
	{
		$body = '<p>Dear ' . $name . ',</p>'
		.'<p>Thank you for your contribution of '. ($currency == 'USD' ? '$' : '€') . $amount . '! We value your support!</p>'
		.'<p>The Moscow Times Team</p>';


		$this->email->message(
			'Your contribution to The Moscow Times',
			$email,
			'development@' . $this->config['domain'],
			$body
		)->send();
	}


	public function recurringDonationFirst($email, $name, $amount, $currency = 'USD')
	{
		$body = '<p>Dear ' . $name . ',</p>'
		.'<p>Thank you for your contribution of '. ($currency == 'USD' ? '$' : '€') . $amount . '! We value your support!</p>'
		.'<p>The Moscow Times Team</p>';

		$this->email->message(
			'Your first contribution to The Moscow Times',
			$email,
			'development@' . $this->config['domain'],
			$body
		)->send();
	}


	public function recurringDonation($email, $name, $amount, $currency = 'USD')
	{
		$body = '<p>Dear ' . $name . ',</p>'
		.'<p>Thank you for your contribution of '. ($currency == 'USD' ? '$' : '€') . $amount . '! We value your support!</p>'
		.'<p>The Moscow Times Team</p>';

		$this->email->message(
			'Your ongoing contribution to The Moscow Times',
			$email,
			'development@' . $this->config['domain'],
			$body
		)->send();
	}
}


