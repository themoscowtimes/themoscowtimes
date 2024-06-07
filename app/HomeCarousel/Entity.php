<?php

namespace HomeCarousel;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'home_carousel';

	protected static $columns = [
		'id' => 'int',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
    'title' => 'string',
    'article_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
		'rank' => 'int'
	];

	protected static $relations =  [
    'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
    'article' => ['Article\Entity', 'belongs'],
	];
}