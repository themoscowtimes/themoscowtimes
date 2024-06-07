<?php

namespace Account\Subscription;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'subscription';

	protected static $columns = [
		'id' => 'int',
		'created' => 'datetime',
		'timestamp' => 'timestamp',
		'from' => 'date',
		'to' => 'date',
		'type' => 'string',
		'status' => 'string',
		'psp' => 'string',
		'psp_id' => 'string',
		'data' => 'json',
		'account_id' => 'int',
	];

	protected static $relations =  [
		'account' => ['Account\Entity' ,'belongs'],
	];
}