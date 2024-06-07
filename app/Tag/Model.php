<?php

namespace Tag;

use Sulfur\Data;

class Model
{

	public function __construct(Data $data)
	{
		$this->data = $data;
	}

	public function one($slug)
	{
		return $this->data->finder('Sulfur\Manager\Tag\Entity')
		->where('slug', $slug)
		->one();
	}

	public function purge()
	{
		$tags = $this->data->database()
		->select('tag.id', 'tag.title')
		->from('tag')
		->join('tag_dossier', 'left')->on('tag_dossier.tag_id', 'tag.id')
		->join('tag_article', 'left')->on('tag_article.tag_id', 'tag.id')
		->where('tag_dossier.id', '=', null)
		->where('tag_article.id', '=', null)
		->result('id', 'title');

		if(count($tags) > 0) {
			$delete = $this->data->database()
			->delete('tag')
			->where('id', 'in', array_keys($tags));
			$delete->execute();
		}
		return($tags);
	}
}