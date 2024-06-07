<?php

namespace Article;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'article';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'updated' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'timestamp' => 'timestamp',
		'duration' => 'int',
		'type' => 'string',
		'pushed' => 'boolean',
		'section' => 'string',
		'news' => 'boolean',
		'opinion' => 'boolean',
		'meanwhile' => 'boolean',
		'city' => 'boolean',
		'business' => 'boolean',
		'climate' => 'boolean',
		'diaspora' => 'boolean',
		'ukraine_war' => 'boolean',
		'lecture_series' => 'boolean',
		'russian' => 'boolean',
		'indepth' => 'boolean',
		'living' => 'boolean',
		'sponsored' => 'boolean',
		'analysis' => 'boolean',
		'title' => 'string',
		'title_long' => 'string',
		'title_live' => 'boolean',
		'subtitle' => 'string',
		'intro' => 'text',
		'excerpt' => 'text',
		'excerpt_live' => 'json',
		'summary' => 'text',
		'keyword' => 'string',
		'body' => 'json',
		'link' => 'json',
		'time_publication' => 'datetime',
		'use_time_publication' => 'boolean',
		'views' => 'int',
		'image_id' => 'int',
		'caption' => 'text',
		'credits' => 'text',
		'source' => 'text',
		'image_set_id' => 'int',
		'article_set_id' => 'int',
		'seo' => 'json',
		'video' => 'text',
		'audio' => 'text',
		'lock' => 'string',
		'issue_id' => 'int',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		//'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'partners' => ['Partner\Entity' , 'junction',  'junction' => 'article_partner'],
		'authors' => ['Author\Entity' , 'junction',  'junction' => 'article_author'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'images' => ['Sulfur\Manager\Image\Entity', 'set', 'columns' => ['title' => 'text', 'caption' => 'text', 'credits' => 'string']],
		'articles' => ['Article\Entity', 'set'],
		'tags' => ['Sulfur\Manager\Tag\Entity', 'junction', 'junction' => 'tag_article'],
		'issue' => ['Issue\Entity', 'belongs'],
		'dossiers' => ['Dossier\Entity', 'junction', 'junction' => 'dossier_article'],
	];
}