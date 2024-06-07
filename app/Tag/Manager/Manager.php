<?php

namespace Tag\Manager;

use Sulfur\Manager\Tag\Manager as BaseManager;
use Sulfur\Manager\Payload;

use Tag\Model as Tag;

class Manager extends BaseManager
{

	protected $tag;

	public function __construct(Tag $tag)
	{
		$this->tag = $tag;
	}

	public function purge()
	{
		return new Payload('tag/purged', [
			'tags'=> $this->tag->purge()
		]);
	}
}