<?php

namespace Article\Manager;

use Exception;
use Sulfur\Manager\Manager as BaseManager;
use Sulfur\Data\Finder;
use Sulfur\Cache;
use Sulfur\Logger;
use TelegramBot;


class Manager extends BaseManager
{
	/**
	 * @var Sulfur\Cache
	 */
	protected $cache;

	public function __construct(Cache $cache, Og $og, TelegramBot $telegram, Logger $logger)
	{
		$this->cache = $cache;
		$this->og = $og;
		$this->telegram = $telegram;
		$this->logger = $logger;
	}

	public function index($zone, $module)
	{
		$payload = parent::index($zone, $module);

		// unset search state
		$this->state->set('search', '');

		return $payload;

	}

	public function save($zone, $module, $id = null)
	{
		$payload = parent::save($zone, $module, $id);

		if($article = $this->entity($payload->data('data')['id'] ?? 0)) {
			if(
        $article->type == "gallery"
        && $article->status == 'live'
        && !$article->pushed ||
        (
          $article->type == 'default'
          && ($article->news || $article->opinion || $article->city)
				  && $article->status == 'live'
				  && strtotime($article->time_publication) <= time()
				  && !$article->pushed
        )
			) {
				try {
					$this->telegram->sendMessage($article);
					$article->pushed = true;
				} catch (Exception $e) {
					// $this->logger->error($e->getMessage());
					$data = $payload->data('data');
					$data['success'] = false;
					$data['message'] =  'Your article was saved but not sent to Telegram. ' . $e->getMessage();
					$payload->data('data', $data);
				} finally {
					$this->data->save($article);
				}
			}


			if($image = $article->image) {
				$bg = trim($image->path, '/') . '/' . $image->file;
			} else {
				$bg = null;
			}

			if($article->type == 'live') {
				$title = isset($article->seo['description']) && trim($article->seo['description']) ? $article->seo['description'] : $article->title;
			} else {
				$title = $article->title;
			}

			try{
				$this->og->image($article->id, $title, $bg);
			} catch(Exception $e) {

			}
		}


		if($id) {
			$this->cache->delete('article_' . $id);
			$this->cache->delete('home');
			$this->cache->delete('section_opinion_');
			$this->cache->delete('section_meanwhile_');
			$this->cache->delete('section_news_');
			$this->cache->delete('section_city_');
			$this->cache->delete('section_business_');
			$this->cache->delete('section_climate_');
			$this->cache->delete('section_diaspora_');
		}

		return $payload;
	}


	protected function hydrate($entity, & $relations, $data, $zone, $module)
	{
		// set updated time on every save
		$data['updated'] = date('Y-m-d H:i:s', time());

		// whether user used the time field
		$hasTime = isset($data['time_publication']) && $data['time_publication'];
		// current status of the article
		$isLive = $entity->status === 'live';
		// request has incoming status change to live
		$isPublishing = isset($data['status']) && $data['status'] === 'live';

		if($hasTime) {
			// explicit time used: set use to true
			$data['use_time_publication'] = true;
		} else {
			// no explicit time: set it to now
			$data['time_publication'] = date('Y-m-d H:i:s', time());
			if($isLive || $isPublishing) {
				// no explicit time and article is live or going live: use the publication time of 'now'
				$data['use_time_publication'] = true;
			} else {
				// no explicit time and article not live: dont use puublication time
				// By doing this, the publication time will not be set in the form, (check pouplate())
				$data['use_time_publication'] = false;
			}
		}

		$data['duration'] = 0;
		if(is_array($data['body'])) {
			$words = 0;
			foreach($data['body'] as $block) {
				if(($block['type'] == 'html' || $block['type'] == 'aside') && isset($block['body'])) {
					$words += count(explode(' ' , strip_tags($block['body'])));
				}
			}
			$data['duration'] = ceil($words / 250);
		}

		parent::hydrate($entity, $relations, $data, $zone, $module);
	}


	protected function populate($form, $entity = null)
	{
		parent::populate($form, $entity);

		// set time publication to null when we're not useing it yet
		if(! $entity->use_time_publication) {
			$form->value('time_publication', 0);
		}
	}



	protected function filter(Finder $finder, $filter)
	{
		if(isset($filter['section'])) {
			$finder->where($filter['section'], 1);
			unset($filter['section']);
		}
		parent::filter($finder, $filter);
	}
}