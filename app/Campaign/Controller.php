<?php

namespace Campaign;

use Sulfur\View;
use Sulfur\Form;
use Sulfur\Request;
use Sulfur\Cache;

class Controller
{

	public function preview(Request $request, Model $model, View $view)
	{
		if($item = $model->preview($request->post())) {
			$advertorails = $item->advertorials;
			shuffle($advertorails);
			return $view->render('campaign/item', [
				'item' => $item,
				'advertorials' => $advertorails,
			]);
		}
	}


	public function view(Model $model, View $view, Cache $cache, $slug, $offset = 0)
	{
		$key = 'campaign_' . $slug . '_' . $offset;
		if($html = $cache->get($key)) {
			return $html;
		} else {
			if($item = $model->one($slug) ) {
				$advertorails = $item->advertorials;
				shuffle($advertorails);
				$html = $view->render('campaign/item', [
					'item' => $item,
					'advertorials' => $advertorails,
				]);
				$cache->set($key, $html, 5 * 60);
				return $html;
			} else {
				return $view->render('page/404');
			}
		}
	}
}