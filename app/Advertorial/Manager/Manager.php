<?php

namespace Advertorial\Manager;

use Sulfur\Manager\Manager as BaseManager;
use Sulfur\Response;

use Sulfur\Data\Finder;
use Sulfur\Cache;
use Manager\Campaign;
class Manager extends BaseManager
{


	public function __construct(Campaign $campaign)
	{
		$this->campaign = $campaign;
	}


	public function index($zone, $module)
	{
		$payload = parent::index($zone, $module);
		$payload->view('campaign/index');
		$payload->data('campaigns', $this->data->finder('Campaign\Entity')->order('title')->all());
		$payload->data('active', $this->campaign->active());
		return $payload;
	}

	protected function filter(Finder $finder, $filter)
	{
		parent::filter($finder, $filter);
		$finder->where('campaign_id', $this->campaign->active());
	}


	protected function hydrate($entity, & $relations, $data, $zone, $module)
	{
		// set the active campaign
		$data['campaign_id']  = $this->campaign->active();

		// set updated time on every save
		$data['updated'] = date('Y-m-d H:i:s', time());

		// whether user used the time field
		$hasTime = isset($data['time_publication']) && $data['time_publication'];
		// current status of the article
		$isLive = $entity->status === 'live';
		// request has incoming status change to live
		$isPublishing = isset($data['status']) && $data['status'] === 'live';
		// request has incoming status change to live
		$isDepublishing = isset($data['status']) && $data['status'] === 'edit';

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
}