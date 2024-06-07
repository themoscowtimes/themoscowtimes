<?php

namespace Dossier;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'dossier';

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
		'label' => 'string',
		'image_id' => 'int',
		'caption' => 'text',
		'credits' => 'text',
		'video' => 'string',
		'title_live' => 'boolean',
		'seo' => 'json',
		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'image' => ['Sulfur\Manager\Image\Entity', 'belongs'],
		'tags' => ['Sulfur\Manager\Tag\Entity', 'junction', 'junction' => 'tag_dossier'],
		'articles' => ['Article\Entity', 'junction', 'junction' => 'dossier_article'],
	];
}