<?php

namespace Archive\Author;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'archive_author';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'title' => 'string',
		'excerpt' => 'text',
		'body' => 'text',
		'twitter' => 'text',
		'archive_image_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'articles' => ['\Archive\Article\Entity' ,'junction', 'junction' => 'archive_article_author'],
		'image' => ['Archive\Image\Entity', 'belongs'],
	];
}