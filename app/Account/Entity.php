<?php

namespace Account;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'account';

	protected static $columns = [
		'id' => 'int',
		'uid' => ['string', 'index' => true],
		'confirm' => ['string', 'index' => true],
		'permanent' => ['string', 'index' => true],
		'reset' => ['string', 'index' => true],
		'email' => ['string', 'index' => true],
		'username' => ['string', 'index' => true],
		'facebook' => ['string', 'index' => true],
		'password' => 'string',
		'pbk' => 'string',
		'created' => 'datetime',
		'agreed' => 'datetime',
		'confirmed' => 'datetime',
		'visited' => 'datetime',
		'failed' => 'json',
		'agents' => 'json',
		'ips' => 'json',
		'public' => 'json',
		'protected' => 'text',
		'private' => 'text',
		'timestamp' => 'timestamp',
	];

	protected static $relations =  [
		'subscriptions' => ['Account\Subscription\Entity' ,'many'],
		//'profile' => ['Account\Profile\Entity' ,'one'],
	];
}



/*
ALTER TABLE `account`
	ADD COLUMN `salt` VARCHAR(255) NOT NULL DEFAULT '' AFTER `password`,
	ADD COLUMN `ips` TEXT NULL AFTER `agents`;

	ALTER TABLE `account`
	ADD COLUMN `data` TEXT NULL AFTER `type`;
 *
 * ALTER TABLE `account`
	CHANGE COLUMN `data` `public` TEXT NULL AFTER `type`,
	ADD COLUMN `protected` TEXT NULL AFTER `public`,
	ADD COLUMN `private` TEXT NULL AFTER `protected`;
 */