<?php

use Sulfur\Request;
use Sulfur\Data;

class Menu
{

	protected $data;

	protected $map = null;

	protected $tree = [];


	public function __construct(Data $data)
	{
		$this->data = $data;
	}


	public function get($name)
	{
		$this->map();
		foreach($this->tree as $entity) {
			if($entity->tag == $name) {
				return $entity->children;
			}
		}
	}


	protected function map()
	{
		if($this->map === null) {
			$this->map = [];
			$entities = $this->data->finder('Sulfur\Manager\Menu\Entity')
			->order('rank', 'ASC')
			->all();

			foreach($entities as $entity) {
				$entity->children = [];
				$this->map[$entity->id] = $entity;
			}

			foreach($entities as $entity) {
				if($entity->parent_id == 0) {
					$this->tree[] = $entity;
				} elseif(isset($this->map[$entity->parent_id])) {
					$children = $this->map[$entity->parent_id]->children;
					$children[] = $entity;
					$this->map[$entity->parent_id]->children = $children;
				}
			}
		}
	}
}