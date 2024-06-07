<?php

namespace Partner;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'partner';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'excerpt' => 'text',
		'body' => 'text',
		'image_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'articles' => ['Article\Entity' ,'junction', 'junction' => 'article_partner'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
	];
}