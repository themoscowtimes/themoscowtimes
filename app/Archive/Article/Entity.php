<?php

namespace Archive\Article;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'archive_article';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'type' => 'string',
		'section' => 'string',
		'sponsored' => 'boolean',
		'title' => 'string',
		'subtitle' => 'string',
		'intro' => 'text',
		'excerpt' => 'text',
		'keyword' => 'string',
		'body' => 'json',
		'link' => 'json',
		'time_publication' => 'datetime',
		'use_time_publication' => 'boolean',
		'views' => 'int',
		'archive_image_id' => 'int',
		'caption' => 'text',
		'credits' => 'text',
		'source' => 'text',
		'archive_image_set_id' => 'int',
		'seo' => 'json',
		'video' => 'text',
		'audio' => 'text',
		'lock' => 'string',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'authors' => ['Archive\Author\Entity' , 'junction',  'junction' => 'archive_article_author'],
		'image' => ['Archive\Image\Entity', 'belongs'],
		'images' => ['Archive\Image\Entity', 'set', 'junction' => 'archive_image_set', 'columns' => ['title' => 'text', 'caption' => 'text', 'credits' => 'string']],
	];
}