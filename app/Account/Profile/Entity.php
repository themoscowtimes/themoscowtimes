<?php

namespace Account\Profile;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'profile';

	protected static $columns = [
		'id' => 'int',
		'created' => 'datetime',
		'timestamp' => 'timestamp',
		'screenname' => 'string',
		'avatar' => 'string',
		'user_id' => 'int',
		'status' => 'string',
		'lang' => 'string',
		'zone' => 'string',
	];

	protected static $relations =  [
		'account' => ['Sulfur\Manager\User\Entity' ,'belongs'],
	];
}