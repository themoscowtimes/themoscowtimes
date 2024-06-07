<?php

namespace Advertorial;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
		$this->data = $data;
	}


	public function preview($post)
	{
		if(isset($post['values'])) {
			$values = json_decode(base64_decode($post['values']), true);

			if(! $values['time_publication']) {
				$values['time_publication'] = date('Y-m-d H:i:s');
			}
			$preview =  $this->data->hydrate('Advertorial\Entity', $values);

			if(
				$advertorial = $this->data->finder('Advertorial\Entity')
				->with('campaign')
				->with('campaign.logo')
				->where('slug', $preview->slug)
				->one()
			) {
				$preview->campaign = $advertorial->campaign;
			} else {
				$preview->campaign = null;
			}

			return $preview;
		}
	}


	public function one($id)
	{
		$advertorial = $this->data->finder('Advertorial\Entity')
		//->with('authors', function($finder){
		//	$finder->where('status', 'live');
		//})
		//->with('authors.image')
		->with('campaign')
		->with('campaign.logo')
		->with('image')
		->with('images')
		->where('id', $id);

		$this->live($advertorial);

		return $advertorial->one();
	}


	public function all()
	{
		$advertorials = $this->data->finder('Advertorial\Entity')
		->with('campaign')
		->with('campaign.logo')
		->with('image')
		->where('campaign.vtimes', 0);

		$this->live($advertorials);
		$this->newest($advertorials);

		return $advertorials->all(true);
	}


	public function vtimes()
	{
		$advertorials = $this->data->finder('Advertorial\Entity')
		->with('campaign')
		->with('campaign.logo')
		->with('image')
		->where('campaign.vtimes', 1);

		$this->live($advertorials);
		$this->newest($advertorials);

		return $advertorials->all(true);
	}



	public function other($campaignId, $advertorialId)
	{
		$advertorials = $this->data->finder('Advertorial\Entity')
		->with('image')
		->where('campaign_id', $campaignId)
		->where('id', '<>' ,$advertorialId);


		$this->live($advertorials);
		$this->newest($advertorials);

		return $advertorials->all(true);
	}


	public function slug($slug)
	{
		$advertorial = $this->data->finder('Advertorial\Entity')
		->where('slug', $slug);

		$this->live($advertorial);

		return $advertorial->one();
	}



	public function id($id)
	{
		return $this->data->finder('Advertorial\Entity')
		->where('id', $id)
		->one();
	}


	public function viewed($id)
	{
		$database = $this->data->database(\Advertorial\Entity::database());
		$database
		->update('advertorial')
		->set([
			'views' => $database->raw('views + 1')
		])
		->where('id', $id)
		->execute();
	}



	public function live($finder)
	{
		$finder
		->join('campaign', 'inner')
		->on('campaign.id', 'advertorial.campaign_id')
		->where('advertorial.status', 'live')
		->where('campaign.status', 'live')
		->where('advertorial.time_publication', '<', date('Y-m-d H:i:s'));

		return $finder;
	}


	public function newest($finder)
	{
		$finder
		->order('time_publication', 'desc');
		return $finder;
	}

}