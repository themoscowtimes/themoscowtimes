<?php

namespace Campaign\Manager;

use Sulfur\Manager\Manager as BaseManager;
use Sulfur\Response;

use Manager\Campaign;

class Manager extends BaseManager
{
	public function __construct(Campaign $campaign)
	{
		$this->campaign = $campaign;
	}

	protected function form($name, $zone, $module, $id = null)
	{
		return $this->form->get($name, [
			'id' => $id
		]);
	}

	public function advertorial($zone, $id, Response $response)
	{
		$this->campaign->active($id);
		$response->redirect($this->url->action($zone, 'advertorial', 'create'));
	}


	public function advertorials($zone, $id, Response $response)
	{
		$this->campaign->active($id);
		$response->redirect($this->url->action($zone, 'advertorial', 'index'));
	}
}