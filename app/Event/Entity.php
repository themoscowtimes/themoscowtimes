<?php

namespace Event;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'event';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'subtitle' => 'string',
		'body' => 'text',
		'time' => 'datetime',
		'time_end' => 'datetime',
		'show_time' => 'boolean',
		'show_time_end' => 'boolean',
		'type' => 'string',
		'image_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'locations' => ['Location\Entity', 'junction', 'junction' => 'event_location', 'columns' => ['time' => 'string' , 'info' => 'string']],
	];
}