<?php

namespace Banner;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'banner';

	protected static $columns = [
		'id' => 'int',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'type' => 'string',
		'link' => 'json',
		'positions' => 'json',
		'html' => 'text',
		'image_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs']
	];
}