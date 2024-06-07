<?php

namespace Author;

use Sulfur\Data;
use Article\Model as ArticleModel;

class Model
{
	public function __construct(Data $data, ArticleModel $article)
	{
		$this->data = $data;
		$this->article = $article;
	}

  public function all($char)
	{
		$authors = $this->data->finder('Author\Entity')
		->with('image')
		->where('status', 'live')
		->all();

		// Convert collection to array
		$arr = [];
		foreach ($authors as $key => $author)
		{
			$arr[$key] = $author;
		}

		$filtered = array_filter($arr, function ($author) use ($char) {
			$name = explode(' ', $author->title);
			$last_name = count($name) > 1 ? end($name) : $name[0];
			return (!empty($last_name) ? strtolower($last_name[0]) == strtolower($char) : $last_name);
		});

		return $filtered;
	}

	public function one($slug)
	{
		return $this->data->finder('Author\Entity')
		->with('image')
		->where('status', 'live')
		->where('slug', $slug)
		->one();
	}

	public function id($id)
	{
		return $this->data->finder('Author\Entity')
		->where('id', $id)
		->one();
	}

	public function articles($authorId, $amount = 10)
	{
		return $this->article->author($authorId, $amount);
	}


	public function set(array $ids = [])
	{
		if(count($ids) > 0) {
			// escape ids for raw db input
			array_walk($ids, function($id){ return (int) $id; });

			// get finder
			$finder = $this->data->finder('Author\Entity');

			// get authors
			$authors = $finder
			->with('image')
			->where('id', 'in', $ids)
			->order($finder->raw('FIELD(id,' . implode(',', $ids) . ' )'), 'ASC');
			return $authors->all();
		} else {
			return [];
		}
	}
}
