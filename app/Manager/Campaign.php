<?php

namespace Manager;

use Sulfur\Manager\State;

class Campaign {

	public function __construct(State $state) {
		$this->state = $state;
	}

	public function active($id = null)
	{
		if($id === null) {
			return $this->state->get('campaign');
		} else {
			$this->state->module('advertorial');
			$this->state->set('campaign', $id);
		}
	}
}
