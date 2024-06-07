<?php

namespace Advertorial;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'advertorial';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'updated' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'type' => 'string',
		'section' => 'string',
		'title' => 'string',
		'subtitle' => 'string',
		'intro' => 'text',
		'excerpt' => 'text',
		'keyword' => 'string',
		'body' => 'json',
		'time_publication' => 'datetime',
		'use_time_publication' => 'boolean',
		'views' => 'int',
		'image_id' => 'int',
		'caption' => 'text',
		'credits' => 'text',
		'source' => 'text',
		'image_set_id' => 'int',
		'seo' => 'json',
		'video' => 'text',
		'audio' => 'text',
		'lock' => 'string',
		'campaign_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];


	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'campaign' => ['Campaign\Entity', 'belongs'],
		//'authors' => ['Author\Entity', 'junction',  'junction' => 'article_author'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'images' => ['Sulfur\Manager\Image\Entity', 'set', 'columns' => ['title' => 'text', 'caption' => 'text', 'credits' => 'string']],
	];
}
