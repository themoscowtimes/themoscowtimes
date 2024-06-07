<?php

namespace Advertorial;

use Sulfur\View;
use Sulfur\Request;
use Sulfur\Response;
use Sulfur\Cache;
use Url;

class Controller
{

	public function preview($hash = null, Request $request, Response $response, Url $url, Model $model, View $view, Cache $cache)
	{
/*
		if($hash !== null) {
			if($item = $cache->get('advertorial_' . $hash)) {
				return $item;
			} else {
				return 'Preview no longer available';
			}
		}
*/
		if($advertorial = $model->preview($request->post())) {

			$item = $view->render('advertorial/item', [
				'item' => $advertorial,
				'campaign' => $advertorial->campaign,
				'advertorials' => $model->other($advertorial->campaign_id, $advertorial->id),
			]);
			return $item;

/*
			$hash = md5(random_bytes(30));

			$cache->set('advertorial_' . $hash, $item, 5 * 24 * 3600);

			if($cached = $cache->get('advertorial_' . $hash)) {
				// check if it worked: if so, redirect
				$response->redirect($url->route('advertorialpreview', ['hash' => $hash]));
			} else {
				// if not: at least show the preview
				return $item;
			}
 */
		}
	}


	public function view($slug, Model $model, View $view, Request $request, Response $response, Url $url,  Cache $cache)
	{
		if($item = $model->slug($slug)){
			$model->viewed($item->id);
			$key = 'advertorial_' . $item->id;
			if($html = $cache->get($key)) {

			} else {
				// get full item
				$item = $model->one($item->id);
				$advertorails = $model->other($item->campaign_id, $item->id);
				shuffle($advertorails);
				$html = $view->render('advertorial/item', [
					'item' => $item,
					'campaign' => $item->campaign,
					'advertorials' => $advertorails,
				]);
				$cache->set($key, $html, 5 * 60);
			}
			return $html;
		} else {
			return $view->render('page/404');
		}
	}



	public function vtimes(Model $model, View $view)
	{
		return $view->render('advertorial/vtimes', [
			'items' => $model->vtimes(),
		]);
	}
}