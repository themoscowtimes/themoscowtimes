<?php

namespace Archive\Image;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'archive_image';

	protected static $columns = [
		'id' => 'int',
		'created' => 'datetime',
		'timestamp' => 'timestamp',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live']],
		'title' => 'string',
		'file' => 'string',
		'path' => 'string',
		'width' => 'int',
		'height' => 'int',
		'user_id' => 'int',
		'zone' => ['string', 'index' => true],
	];
}