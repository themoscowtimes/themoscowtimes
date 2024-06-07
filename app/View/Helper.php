<?php

namespace View;


use Sulfur\Container;
use Sulfur\View;
use Sulfur\Config;
use Sulfur\Request;

use Url;
use Menu;
use Banner\Model as Banner;
use HomeCarousel\Model as HomeCarousel;
use TelegramRussian\Model as TelegramRussian;
use Settings;
use Article\Model as Article;
use Sulfur\Cache as Cache;
use Lang;
use Bites;
use Covid19;

use Sulfur\Dom;


class Helper
{
	protected $container;
	protected $request;
	protected $view;
	protected $config;
	protected $url;
	protected $menu;
	protected $banner;
	protected $carousel;
	protected $telegram_russian;
	protected $settings;
	protected $article;
	protected $cache;
	protected $bites;


	public function __construct(
		Container $container,
		Request $request,
		View $view,
		Config $config,
		Url $url,
		Menu $menu,
		Banner $banner,
		HomeCarousel $carousel,
		TelegramRussian $telegram_russian,
		Settings $settings,
		Article $article,
		Cache $cache,
		Lang $lang,
		Bites $bites,
		Covid19 $covid19
	)
	{
		$this->container = $container;
		$this->request = $request;
		$this->view = $view;
		$this->config = $config;
		$this->url = $url;
		$this->menu = $menu;
		$this->banner = $banner;
		$this->settings = $settings;
		$this->carousel = $carousel;
		$this->telegram_russian = $telegram_russian;
		$this->article = $article;
		$this->cache = $cache;
		$this->lang = $lang;
		$this->bites = $bites;
		$this->covid19 = $covid19;
	}


	public function register()
	{
		// Register helpers
		$this->view->helper([
			'csrf' => function() {
				return $this->container->get('Sulfur\Csrf')->token();
			},
			'version' => function() {
				return $this->config->env('version', 1);
			},
			'settings' => function($name) {
				return $this->settings->get($name);
			},
			'header' => function($name) {
				return $this->view->render('header/' . $name);
			},
			'contribute' => function($size = null) {
				if(strpos($this->request->path(), 'contribute') === false)	{
					return $this->view->render('contribute/banner', [
						'size' => $size
					]);
				} else {
					return '';
				}
			},
			'menu' => function($name = 'main') {
				$key = 'menu_' . $name;
				if($menu = $this->cache->get($key)) {
					return $menu;
				} else {
					$menu = $this->view->render('menu/' . $name, ['tree' => $this->menu->get($name)]);
					$this->cache->set($key, $menu, 300);
					return $menu;
				}
			},
			'dom' => function($tag, $attributes = [], $text = '') {
				return new Dom($tag, $attributes, $text);
			},
			'truncate' => function($string, $length = 150, $ellepsis = '...') {
				if(strlen($string) <= $length) {
					return $string;
				} else {
					$truncated = substr($string, 0, $length);
					while($length < strlen($string) && ! in_array(substr($string, $length, 1), [' ', ',', ';', '.', '!', '?'] )){
						$length ++;
						$truncated = substr($string, 0, $length);
					}
					if(strlen($truncated) <= $length) {
						$truncated .= $ellepsis;
					}
					return $truncated;
				}
			},

			'bem' => function($block, $context = null, $modifier = null) {
				return new Class($block, $context, $modifier) {
					public function __construct($block, $context = null, $modifier = null) {
						$this->block = $block;
						$this->context = $context;
						$this->modifier = $modifier;
					}
					public function __invoke($element = null) {
						if($element === null) {
							$element = $this->context ? (' ' . $this->context . '__' . $this->block) : '';
							$modifier = $this->modifier ? (' ' . $this->block . '--' . $this->modifier) : '';
							$elementModifier = $this->context && $this->modifier? (' ' . $this->context . '__' . $this->block. '--' . $this->modifier) : '';
							return $this->block . $element . $modifier . $elementModifier;
						} else {
							return $this->block . '__' . $element;
						}
					}
					public function block() { return $this->block; }
					public function context() { return $this->context; }
					public function modifier() { return $this->modifier; }

				};
			},
			'bites' => function(){
				return $this->bites->data();
			},
			'link' => function($link, $text = null) {
				$link = is_array($link) ? $link : [];
				$href = isset($link['url']) ? $link['url'] : '#';
				$blank = isset($link['blank']) && $link['blank'] == '1' ? ' target="_blank"' : '';
				$nofollow = isset($link['nofollow']) && $link['nofollow'] == '1' ? ' rel="nofollow"' : '';
				$text = $text === null ? (isset($link['title']) ? $link['title'] : '') : $text;
				return '<a href="' . htmlspecialchars($href) . '" ' . $blank . $nofollow . '>' . htmlspecialchars($text) . '</a>';
			},
			'src' => function($image, $preset = '640', $archive = false) {

				if(is_array($image) && isset($image['file'])) {
					$file = $image['file'];
					$path = isset($image['path']) ? $image['path'] : null;
				} elseif(is_object($image) && isset($image->file)) {
					$file = $image->file;
					$path = isset($image->path) ? $image->path : null;
				} elseif(is_string($image) || is_int($image)) {
					$file = $image;
					$path = null;
				} else {
					return '';
				}

				return $this->url->route(($archive ? 'image_archive' : 'image'), [
					'file' => $file,
					'path' => trim($path, '/\\'),
					'preset' => $preset,
				]);
			},
			'env' => function($name, $default = null) {
				return $this->config->env($name, $default);
			},
			'route' => function($name, $params = []){
				return $this->url->route($name, $params);
			},
			'url' => function($name = null ) {
				if ($name === 'static') {
					return $this->config->env('url.static');
				} elseif ($name === 'base'){
					return $this->url->base();
				} elseif ($name === 'current'){
					return $this->url->current();
				}
			},
			'lang'=> function($text) {
				$lang = $this->lang->main($text, $text);
				if(is_array($lang)) {
					$lang = $text;
				}
				return $lang;
			},
			'date' => function($datetime, $time = false) {
				$timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
				if($time) {
					$date =  date('M. j, Y - H:i', $timestamp);
				} else {
					$date =  date('M. j, Y', $timestamp);
				}
				$date = str_replace(['Mar.', 'Apr.', 'May.', 'Jun.', 'Jul.'], ['March', 'April', 'May', 'June', 'July'],  $date);
				return $date;
			},
			'time' => function($datetime) {
				$timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
				$date =  date('g:i A', $timestamp);
				return $date;
			},
			'banner' => function($position) {
				static $banners;
				$key = 'banner' . $position;
				if($banner = $this->cache->get($key)) {
					return $banner;
				} else {
					if(is_null($banners)) {
						$banners = $this->banner->all();
					}
					$pool = [];
					foreach($banners as $banner) {
						if(is_array($banner->positions) && in_array($position, $banner->positions)) {
							$pool[] = $banner;
						}
					}
					if(count($pool) > 0) {
						$banner = $this->view->render('banner/item', [
							'position' => $position,
							'banners' => $pool
						]);
					} else {
						$banner = '   ';
					}
					$this->cache->set($key, $banner, 300);
					return $banner;
				}
			},
			'seo' => function($type, $data = []) {
				return $this->view->render('seo/' . $type, $data);
			},
			'section' => function($article) {
				$sections = ['ukraine_war', 'sponsored', 'opinion', 'news',  'meanwhile', 'city', 'business', 'climate', 'diaspora', 'indepth', 'lecture_series',  'russian'];
				foreach($sections as $section) {
					if($article->{$section}) {
						return $section;
					}
					return 'news';
				}
			},
			'manager' => function($module, $id) {
				return '<!--[[['. $module .':' . $id .  ']]]-->';
			},
			'recent' => function() {
				$key = 'recent';
				if($recent = $this->cache->get($key)) {
					return $recent;
				} else {
					$recent = $this->view->render('article/recent', ['items' => $this->article->recent(7)]);
					$this->cache->set($key, $recent, 120);
					return $recent;
				}
			},
			'mostread' => function() {
				$key = 'mostread';
				if($mostread = $this->cache->get($key)) {
					return $mostread;
				} else {
					$mostread = $this->view->render('article/mostread', ['items' => $this->article->mostread(5)]);
					$this->cache->set($key, $mostread, 120);
					return $mostread;
				}
			},
			'covid19' => function () {	$key = 'covid19';
				if($data = $this->cache->get($key)) {
					 return $data;
				} else {
					$data = $this->covid19->data();
					$this->cache->set($key, $data, 120);
					return $data;
				}
				return $this->covid19->data();
			},
			'config' => function($resource, $key = null, $default = null) {
				return $this->config->resource($resource, $key, $default);
			},
			'home_carousel' => function () {
				static $carouselSlides;
				if (is_null($carouselSlides)) {
				  $carouselSlides = $this->carousel->all();
				}
				$slidesPool = [];
				foreach($carouselSlides as $slide) {
				  $slidesPool[] = $slide;
				}
				return $slidesPool;
			},
			'telegram_russian' => function () {
				$posts = $this->telegram_russian->all();
				$pool = [];
				foreach($posts as $post) {
					$pool[] = $post;
				}
				return $pool;
			}
		]);
	}
}