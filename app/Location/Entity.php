<?php

namespace Location;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'location';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'description' => 'text',
		'type' => 'string',
		'open' => 'string',
		'metro' => 'string',
		'address' => 'string',
		'phone' => 'string',
		'website' => 'string',
		'twitter' => 'string',
		'facebook' => 'string',
		'image_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
	];
}