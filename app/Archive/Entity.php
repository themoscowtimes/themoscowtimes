<?php

namespace Archive;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'archive_home';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'timestamp' => 'timestamp',

		'image_id' => 'int',
		'image_set_id' => 'int',
		'issue_set_id' => 'int',
		'articles' => 'json',

		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'issues' => ['Issue\Entity', 'set', 'from' => 'issue_set_id'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'images' => ['Sulfur\Manager\Image\Entity', 'set', 'from' => 'image_set_id'],
	];
}