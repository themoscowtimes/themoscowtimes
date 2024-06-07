<?php

use Sulfur\Config;

class Lang extends Config
{
	protected $lang;


	public function setLang($lang = null)
	{
		if($lang === null) {
			return $this->lang;
		} else {
			$this->lang = $lang;
			return $this;
		}
	}

	protected function data($resource)
	{
		$resource = ($this->lang ? $this->lang . '/' : '') . $resource;
		return parent::data($resource);
	}
}