<?php

namespace Issue;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'issue';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'number' => 'string',
		'intro' => 'string',
		'date' => 'date',
		'file_id' => 'int',
		'image_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'file' => ['Sulfur\Manager\File\Entity', 'belongs'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'articles' => ['Article\Entity', 'many']
	];
}