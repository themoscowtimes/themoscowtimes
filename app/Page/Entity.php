<?php

namespace Page;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'page';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'subtitle' => 'string',
		'intro' => 'string',
		'body' => 'text',
		'image_id' => 'int',
		'file_set_id' => 'int',
		'caption' => 'text',
		'credits' => 'text',
		'seo' => 'json',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'files' => ['Sulfur\Manager\File\Entity', 'set', 'columns' => ['title' => 'text']],
	];
}