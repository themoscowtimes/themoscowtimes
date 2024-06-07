<?php

namespace Newsletter;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Form;
use Sulfur\View;
use Newsletter\Model;
use Lang;

class Controller
{

	public function index(View $view, Model $model)
	{
		return $view->render('newsletter/index', [
			'campaignsBell' => $model->previewUrl('bell'),
			'campaigns' => $model->previewUrl('default')
		]);
	}

	public function form(Form $form, View $view)
	{
		return $view->render('newsletter/item', ['form' => $form->get('Newsletter\Form')]);
	}


	public function preview(Model $model, Response $response, $type)
	{
		$response->redirect($model->previewUrl($type));
	}


	public function submit(Form $form, Model $newsletter, Lang $lang)
	{
		$success = false;
		$message = 'done';
		$form = $form->get('Newsletter\Form');
		if($form->valid()) {
			if($newsletter->signupInfo($form->values())) {
				$success = true;
			} else {
				$message = $newsletter->error();
			}
		} else {
			foreach( $form->errors() as $field => $errors) {
				$message = $lang->main('error.' . $field . '.' . $errors[0]);
			}
		}
		return json_encode([
			'success' => $success,
			'message' => $message
		]);
	}


	public function signup(Request $request, Model $newsletter)
	{
		$success = false;
		$message = 'done';
		if($email = $request->post('email', false)) {
			// signup from post
			if($result = $newsletter->signupInfo($request->post())) {
				$success = true;
			} else {
				$message = $newsletter->error();
			}
		} else {
			// signup from raw data
			if($newsletter->signupInfo(json_decode(file_get_contents('php://input'), true))) {
				$success = true;
			} else {
				$message = $newsletter->error();
			}
		}
		return json_encode([
			'success' => $success,
			'message' => $message
		]);
	}


}