<?php

namespace Home;

use Sulfur\Request;
use Sulfur\Response;
use Sulfur\View;
use Sulfur\Cache;
use Ambassador\Model as Ambassador;

class Controller
{
	public function preview(Request $request, Model $model, View $view, Ambassador $ambassador)
	{
		if($home = $model->preview($request->post())) {
			$model->home($home);

			$dossiers = [
				$model->dossier(4, 'dossier_1'),
				$model->dossier(4, 'dossier_2'),
				$model->dossier(4, 'dossier_3')
			];

			$todayDossier = (
				($dossiers[0] && $dossiers[0]->location == 1 )
				||($dossiers[1] && $dossiers[1]->location == 1)
				|| ($dossiers[2] && $dossiers[2]->location == 1)
			);

			return $view->render('home/index', [
				'today' => $model->today(6),
				'highlights' => $model->highlights(3),
				'feature' => $model->feature(),
				'pick' => $model->pick(),
				'live' => $model->live(),
				'opinion' => $model->section('opinion', 6),
				'meanwhile' => $model->section('meanwhile', 4),
				'business' => $model->section('business', 3),
				'climate' => $model->section('climate', 4),
				'diaspora' => $model->section('diaspora', 4),
				'city' => $model->section('city', 4),
				'indepth' => $model->section('indepth', 5),
				'living' => $model->section('living', 4),
				'events' => $model->events(5),
				'podcast' => $model->podcasts(1)[0],
				'video' => $model->videos(1)[0],
				'videos' => $model->videos(20),
				'photos_videos' => $model->photosVideos(10),
				'todayDossier' => $todayDossier,
				'dossiers' => $dossiers,
				'advertorials' => $model->advertorials(2),
				'ambassadors' => $ambassador->all(),
			]);
		}
	}


	public function index(Model $model, View $view, Cache $cache, Response $response, Ambassador $ambassador)
	{
		$response->header('Cache-Control', 'max-age=60, 60, public');
		$key = 'home';
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$dossiers = [
				$model->dossier(4, 'dossier_1'),
				$model->dossier(4, 'dossier_2'),
				$model->dossier(4, 'dossier_3')
			];

			$todayDossier = (
				($dossiers[0] && $dossiers[0]->location == 1 )
				||($dossiers[1] && $dossiers[1]->location == 1)
				|| ($dossiers[2] && $dossiers[2]->location == 1)
			);

			$html = $view->render('home/index', [
				'today' =>  $model->today($todayDossier ? 6 : 7),
				'highlights' => $model->highlights(5),
				'feature' => $model->feature(),
				'pick' => $model->pick(),
				'live' => $model->live(),
				'opinion' => $model->section('opinion', 6),
				'meanwhile' => $model->section('meanwhile', 4),
				'business' => $model->section('business', 3),
				'climate' => $model->section('climate', 4),
				'diaspora' => $model->section('diaspora', 4),
				'city' => $model->section('city', 4),
				'indepth' => $model->section('indepth', 5),
				'living' => $model->section('living', 4),
				'events' => $model->events(5),
				'podcast' => $model->podcasts(1)[0],
				'video' => $model->videos(1)[0],
				'videos' => $model->videos(20),
				'photos_videos' => $model->photosVideos(10),
				'todayDossier' => $todayDossier,
				'dossiers' => $dossiers,
				'advertorials' => $model->advertorials(2),
				'sponsored' => $model->sponsored(1),
				'ambassadors' => $ambassador->all(),
			]);
			$cache->set($key, $html, 60);
			return $html;
		}
	}
}