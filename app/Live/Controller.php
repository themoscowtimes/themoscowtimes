<?php

namespace Live;

use Article\Model as Article;
use Sulfur\Cache;
use Sulfur\Response;

class Controller
{

	public function view(Cache $cache, Article $article, Model $model, Response $response, $id, $from = 0)
	{
		$seconds = 60;
		$from = floor($from / $seconds) * $seconds;
		$response->header('Cache-Control', 'max-age=' . $seconds . ', ' . $seconds . ', public');
		$key = 'live2_' . $id . '_' . $from;
		if($json = $cache->get($key)) {

		} else {
			$json = json_encode($model->all($id, $from));
			$cache->set($key, $json, 60);
		}
		return $json;
	}
}