<?php

namespace Live;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'live';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['draft', 'live'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'time' => 'int',
		'title' => 'string',
		'body' => 'json',
		'article_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];


	protected static $relations =  [

	];
}