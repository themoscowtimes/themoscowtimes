<?php

namespace TelegramRussian;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'telegram_russian';

	protected static $columns = [
		'id' => 'int',
		'user_id' => 'int',
		'created' => 'datetime',
		'status' => ['enum', 'values' => ['edit', 'review' ,'live','deleted'], 'default' => 'live', 'index' => true],
		'title' => 'string',
		'title_ru' => 'string',
		'telegram_post_id' => 'int',
		'zone' => 'string'
	];

	protected static $relations =  [
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
	];
}