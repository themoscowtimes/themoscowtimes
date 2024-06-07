<?php

namespace Archive\Article\Manager;

use Sulfur\Manager\Manager as BaseManager;
use Sulfur\Data\Finder;
use Sulfur\Cache;
use Search\Model as Search;

class Manager extends BaseManager
{
	/**
	 * @var Sulfur\Cache
	 */
	protected $cache;

	public function __construct(Cache $cache, Search $search)
	{
		$this->cache = $cache;
		$this->search = $search;
	}

	public function index($zone, $module)
	{
		$payload = parent::index($zone, $module);

		// unset search state
		$this->state->set('search', '');
		$payload->view('archivearticle/index');
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
		if(isset($filter['author'])) {
			$authors = $this->search->archiveAuthors($filter['author']);
			$ids = [-1];
			foreach($this->search->archiveAuthors($filter['author']) as $author) {
				$ids[] = $author->id;
			}

			$finder->join('archive_article_author', 'left')
			->on('archive_article_author.archive_article_id', 'archive_article.id')
			->where('archive_article_author.archive_author_id', 'in', $ids);

			// search author
			unset($filter['author']);
		}

		if(isset($filter['day'])) {
			$finder->where('time_publication', '>=', $filter['day']);
			$finder->where('time_publication', '<', date('Y-m-d', strtotime($filter['day']) + (24* 3600) ));
			unset($filter['day']);
		}

		if(isset($filter['to'])) {
			$finder->where('time_publication', '<=', $filter['to']);
			unset($filter['to']);
		}

		parent::filter($finder, $filter);
	}


	protected function search(Finder $finder, $search)
	{
		if($search) {
			$articleIds = [-1];
			foreach($this->search->archive($search) as $article) {
				$articleIds[] = $article->id;
			}

			$articleIds = array_unique($articleIds);
			// limit ids
			$articleIds = array_slice($articleIds, 0 , 200);

			// set the finder to find thewse ids
			$finder->where('id', 'in', $articleIds);
			$finder->order($this->data->database()->raw('FIELD(archive_article.id,' . implode(',', $articleIds) . ' )'), 'ASC');
		}
	}
}