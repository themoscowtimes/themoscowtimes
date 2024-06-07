<?php

namespace TelegramApi;

use Api;
use Sulfur\Request;
use Sulfur\Response;
use TelegramBot;

class Controller
{

	public function articles(Request $request, Response $response, Api $api, Model $model)
	{
		if($api->authenticate()) {
			$filters = [
				'from' => strtotime($request->query('from', '1970-01-01'))
			];
			if($type = $request->query('type')) {
				$filters['type'] = $type;
			}
			if($section = $request->query('section')) {
				$filters['section'] = $section;
			}
			$api->respond($response, $model->articles(
				$filters,
				$request->query('limit', 100),
				$request->query('offset', 0)
			));
		} else {
			$api->fail($response, 'request.unauthorized', 401);
		}
	}

	public function handleWebhook(TelegramBot $bot)
	{
		return $bot->handleWebhook();
	}

}