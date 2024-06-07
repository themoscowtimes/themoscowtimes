<?php

namespace Search;

use Sulfur\Cache;
use Sulfur\Request;
use Sulfur\Response;
use Sulfur\View;
use Api;
use Archive\Model as Archive;


class Controller
{
	public function view(Model $search, View $view, $query = '')
	{
		return $view->render('search/view', [
			'query' => $query,
			'archive' => isset($_GET['archive']) ? $search->archive($query) : [],
			'articles' => $search->articles($query),
			'authors' => $search->authors($query),

		]);
	}


	public function index(Cache $cache, Archive $archive, Request $request, View $view)
	{
		$key = 'search_index';
		if($html = $cache->get($key)) {
			return $html;
		} else {
			$html = $view->render('search/index', [
				'image' => $archive->image(),
				'articles' => $archive->articles(),
				'history' => $archive->history(),
				'issues' =>  $archive->issues(),
				'query' => $request->query('q'),
				'from' => strtotime($request->query('from')) ? date('Y-m-d', strtotime($request->query('from'))) : '',
				'to' => strtotime($request->query('to')) ? date('Y-m-d', strtotime($request->query('to'))) : '',
			]);
			$cache->set($key, $html, 1 * 60);
			return $html;
		}
	}


	public function articles(Api $api, Model $model, Response $response)
	{
/*
		$api->respond($response, '[{
        "title": "Tennis Development Brings Safin Back to Moscow",
        "type": "article",
        "weight": "1004575",
        "date": "2011-07-24 22:00:00",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/2011\/07\/24\/tennis-development-brings-safin-back-to-moscow-a8449",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    },
    {
        "title": "Moldova Versus Comic at Tennis",
        "type": "archive",
        "weight": "1004546",
        "date": "2012-08-09 00:00:00",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/archive\/moldova-versus-comic-at-tennis",
        "image": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/image_archive\/article_640\/83\/i189368_cw_2.jpg"
    },
    {
        "title": "Tennis Scandal Shows Russian Officials Relish Offensive Language",
        "type": "archive",
        "weight": "1004543",
        "date": "2014-10-19 21:35:47",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/archive\/tennis-scandal-shows-russian-officials-relish-offensive-language",
        "image": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/image_archive\/article_640\/1d\/i278870_5480-01-tennis2.jpg"
    },
    {
        "title": "Tennis Not Just a Game for Uzbeks",
        "type": "archive",
        "weight": "1004543",
        "date": "2002-08-07 00:00:00",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/archive\/tennis-not-just-a-game-for-uzbeks",
        "image": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/image_archive\/article_640\/52\/i101430_tennis_2.jpg"
    },
    {
        "title": "Safin Duels Fellow ‘Legends of Tennis’",
        "type": "article",
        "weight": "1004542",
        "date": "2011-06-09 22:00:00",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/2011\/06\/09\/safin-duels-fellow-legends-of-tennis-a7532",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    },
    {
        "title": "Tennis Chief on Brink of History",
        "type": "archive",
        "weight": "1004542",
        "date": "2005-09-16 00:00:00",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/archive\/tennis-chief-on-brink-of-history",
        "image": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/image_archive\/article_640\/a7\/i93908_tarpi_2.jpg"
    },
    {
        "title": "Russian Tennis Champion Sharapova Warned Five Times Meldonium Was Banned",
        "type": "article",
        "weight": "1004538",
        "date": "2016-03-09 11:50:15",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/2016\/03\/09\/russian-tennis-champion-sharapova-warned-five-times-meldonium-was-banned-a52095",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    },
    {
        "title": "Russias Sharapovas Ban From Tennis Is Reduced",
        "type": "article",
        "weight": "1004537",
        "date": "2016-10-04 17:11:32",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/2016\/10\/04\/russias-sharapovas-ban-from-tennis-is-reduced-a55593",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    },
    {
        "title": "Russias Maria Sharapova Banned From Tennis",
        "type": "article",
        "weight": "1004535",
        "date": "2016-06-08 17:54:37",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/2016\/06\/08\/russias-maria-sharapova-banned-from-tennis-a53239",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    },
    {
        "title": "Russian Tennis Builds Promise",
        "type": "archive",
        "weight": "1004535",
        "date": "2002-12-03 00:00:00",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/archive\/russian-tennis-builds-promise",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    },
    {
        "title": "Moldova Car Blast Kills Tennis Chief",
        "type": "article",
        "weight": "1004534",
        "date": "2011-06-07 22:00:00",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/2011\/06\/07\/moldova-car-blast-kills-tennis-chief-a7466",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    },
    {
        "title": "Russian Tennis Head Apologizes to Serena Williams",
        "type": "article",
        "weight": "1004534",
        "date": "2014-10-24 12:59:44",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/2014\/10\/24\/russian-tennis-head-apologizes-to-serena-williams-a40733",
        "image": "http://10.0.0.4/projects/themoscowtimes/www_themoscowtimes/public/img/article_default.jpg"
    }]');
		return;

*/

		$api->respond($response, $model->searchArticles(
			$api->get('query', null),
			[
				'section' => $api->get('section', null),
				'from' => $api->get('from', null),
				'to' => $api->get('to', null),
			],
			$api->get('order', null)
		));
	}


	public function authors(Api $api, Model $model, Response $response)
	{
		/*
		$api->respond($response, '[
    {
        "title": "Alexander Bratersky",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/author\/alexander-bratersky"
    },
    {
        "title": "Alexander Golts",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/author\/alexander-golts"
    },
    {
        "title": "Alexander Morozov for Riddle",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/author\/alexander-morozov"
    },
    {
        "title": "Alexander Kosov",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/author\/alexander-kosov"
    },
    {
        "title": "Alexander Bezborodov",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/author\/alexander-bezborodov"
    },
    {
        "title": "Alexander Kramarenko",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/author\/alexander-kramarenko"
    },
    {
        "title": "Alexander Zagnetko",
        "url": "http:\/\/10.0.0.4\/projects\/themoscowtimes\/www_themoscowtimes\/public\/author\/alexander-zagnetko"
    }
	]');
		return;
		 */

		$api->respond($response, $model->searchAuthors(
			$api->get('query', null)
		));
	}
}