<?php

namespace Campaign;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'campaign';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'body' => 'text',
		'home' => 'boolean',
		'vtimes' => 'boolean',
		'image_id' => 'int',
		'caption' => 'text',
		'credits' => 'text',
		'logo_id' => 'int',
		'url' => 'text',
		'seo' => 'json',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'logo' => ['Sulfur\Manager\Image\Entity', 'belongs', 'from' => 'logo_id'],
		'advertorials' => ['Advertorial\Entity', 'many'],
	];
}