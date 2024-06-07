<?php

namespace Newsletter;

class Model
{
	protected $error = '';
	protected $mailchimp;
	protected $mailchimpBell;

	public function __construct(Mailchimp $mailchimp, Mailchimp $mailchimpBell)
	{
		$this->mailchimp = $mailchimp;
		$this->mailchimpBell = $mailchimpBell;
	}


	public function signupEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->signup($email, [], ['WEBSITE SIGNUP', 'Newsletter']);
			return true;
		} else {
			$this->error = 'Please enter a valid email address';
			return false;
		}
	}



	public function signupInfo($data = [])
	{
		// check if data is valid
		if(! is_array($data)) {
			$this->error = 'Please enter an email address';
			return false;
		}

		// check if valid email
		if(! isset($data['email'])) {
			$this->error = 'Please provide an email address';
			return false;
		} else {
			$email = $data['email'];
		}

		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$variables = [];
			foreach($data as $key => $value) {
				if(in_array(strtoupper($key), ['NAME'])) {
					$variables[strtoupper($key)] = $value;
				}
			}

			if(isset($data['tags']) && is_array($data['tags'])) {
				if(isset($data['tags']['newsletter']) && $data['tags']['newsletter'] == '1') {
					$this->signup($email, $variables, ['WEBSITE SIGNUP', 'Newsletter']);
				}
				if(isset($data['tags']['newsletterBell']) && $data['tags']['newsletterBell'] == '1') {
					$this->signupBell($email, $variables, ['TMT WEBSITE SIGNUP']);
				}
			} else {
				// No tags found, just regular signup
				$this->signup($email, $variables, ['WEBSITE SIGNUP', 'Newsletter']);

			}
			return true;
		} else {
			$this->error = 'Please enter a valid email address';
			return false;
		}
	}

	public function previewUrl($type)
	{
		if($type == 'bell') {
			return $this->mailchimpBell->previewUrl('bell');
		} else {
			return $this->mailchimp->previewUrl('default');
		}
	}

	protected function signup($email, $variables = [], $tags = null)
	{
		$this->mailchimp->insert($email, $variables, 'pending', $tags);
	}

	protected function signupBell($email, $variables = [], $tags = null)
	{
		$this->mailchimpBell->insert($email, $variables, 'pending', $tags);
	}

	public function error()
	{
		return $this->error;
	}
}
