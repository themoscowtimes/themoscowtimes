<?php

namespace Home;

use Sulfur\Data\Entity as BaseEntity;

class Entity extends BaseEntity
{
	protected static $database = 'default';

	protected static $table = 'home';

	protected static $columns = [
		'id' => 'int',
		'slug' => 'string',
		'created' => 'datetime',
		'timestamp' => 'timestamp',

		'article_set_1_id' => 'int',
		'article_set_2_id' => 'int',

		'dossier_1_id' => 'int',
		'dossier_1_location' => 'string',

		'dossier_2_id' => 'int',
		'dossier_2_location' => 'string',

		'dossier_3_id' => 'int',
		'dossier_3_location' => 'string',

		'article_feature_id' => 'int',
		'article_pick_id' => 'int',

		'article_opinion_id' => 'int',
		'article_opinion_end' => 'datetime',

		'article_meanwhile_id' => 'int',
		'article_meanwhile_end' => 'datetime',

		'article_business_id' => 'int',
		'article_business_end' => 'datetime',

		'article_city_id' => 'int',
		'article_city_end' => 'datetime',

		'article_indepth_id' => 'int',
		'article_indepth_end' => 'datetime',

		'article_living_id' => 'int',
		'article_living_end' => 'datetime',

		'article_live_id' => 'int',

		'user_id' => 'int',
		'zone' => 'string',
	];

	protected static $relations =  [
		'today' => ['Article\Entity', 'set', 'from' => 'article_set_1_id'],
		'highlights' => ['Article\Entity', 'set', 'from' => 'article_set_2_id'],
		'dossier_1' => ['Dossier\Entity', 'belongs', 'from' => 'dossier_1_id'],
		'dossier_2' => ['Dossier\Entity', 'belongs', 'from' => 'dossier_2_id'],
		'dossier_3' => ['Dossier\Entity', 'belongs', 'from' => 'dossier_3_id'],
		'owner' => ['Sulfur\Manager\User\Entity' ,'belongs'],
		'article_opinion' => ['Article\Entity' ,'belongs', 'from' => 'article_opinion_id'],
		'article_meanwhile' => ['Article\Entity' ,'belongs', 'from' => 'article_meanwhile_id'],
		'article_business' => ['Article\Entity' ,'belongs', 'from' => 'article_business_id'],
		'article_city' => ['Article\Entity' ,'belongs', 'from' => 'article_city_id'],
		'article_indepth' => ['Article\Entity' ,'belongs', 'from' => 'article_indepth_id'],
		'article_living' => ['Article\Entity' ,'belongs', 'from' => 'article_living_id'],
		'article_feature' => ['Article\Entity', 'belongs', 'from' => 'article_feature_id'],
		'article_pick' => ['Article\Entity', 'belongs', 'from' => 'article_pick_id'],
		'article_live' => ['Article\Entity', 'belongs', 'from' => 'article_live_id'],

	];
}