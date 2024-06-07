<?php

namespace Live;

use Sulfur\Data;
use Url;

class Model
{


	protected $error = '';

	public function __construct(Data $data, Url $url)
	{
		$this->data = $data;
		$this->url = $url;
	}

	public function error()
	{
		return $this->error;
	}


	public function all($articleId, $from = 0) {

		$all = [];


		$status = $this->data->database()->select('status')
		->from('article')
		->where('id', $articleId)
		->result(null, 'status')[0] ?? false;

		//temp
		$status = 'live';
		if($status != 'live') {
			return $all;
		}


		$items = $this->data->finder('Live\Entity')
		->where('article_id', $articleId)
		->where('status', 'live')
		->order('time', 'asc')
		->all();

		foreach($items as $item) {
			if($item->time > $from ) {
				$body = [];
				foreach($item->body as $block) {
					if(
						($block['type'] == 'html' && isset($block['body']) && $block['body'])
						|| ($block['type'] == 'embed' && isset($block['embed']) && $block['embed'])
						|| ($block['type'] == 'link' && isset($block['link']) && $block['link'])
					) {
						$body[] = $block;
					} elseif($block['type'] == 'article' && isset($block['article']) && is_array($block['article']) && isset($block['article']['slug'])) {
						$body[] = [
							'type' => 'article',
							'article' => [
								'title' => $block['article']['title'] ?? '',
								'url' => $this->url->route('article', $block['article'])
							]
						];
					} elseif($block['type'] == 'image' && isset($block['image'])) {
						$body[] = [
							'type' => 'image',
							'image' => [
								'src' => $this->url->route('image', [
									'file' => $block['image']['file'] ?? '',
									'path' => trim( $block['image']['path'] ?? '', '/\\'),
									'preset' => 'article_960',
								]),
								'caption' => $block['image']['junction']['caption'] ?? '',
								'credits' => $block['image']['junction']['credits'] ?? ''
							]
						];
					}
				}
				if(count($body) > 0) {
					$all[] = [
						'id' => $item->id,
						'time' => date('c', $item->time),
						'body' => $body,
					];
				}
			}
		}

		return $all;
	}



	public function create($data, $articleId, $userId)
	{
		$articleId = (int) $articleId;
		$userId = (int) $userId;

		if(isset($data['body']) && is_array($data['body'])) {
			$body = $data['body'];
		} else {
			$body = [];
		}

		if(isset($data['status']) && $data['status'] == 'draft') {
			$status = 'draft';
		} else {
			$status = 'live';
		}

		$this->data->create('Live\Entity', [
			'body' => $body,
			'user_id' => $userId,
			'article_id' => $articleId,
			'time' => time(),
			'status' =>$status,
			'zone' => 'main',
		]);
		return true;
	}


	public function update($id, $data)
	{
		$item = $this->data->finder('Live\Entity')->one($id);

		if(! $item) {
			$this->error = 'Post not found';
			return;
		}

		if(isset($data['body']) && is_array($data['body'])) {
			$item->body = $data['body'];
		} else {
			$item->body = [];
		}

		if(isset($data['status']) && $data['status'] == 'draft') {
			$item->status = 'draft';
		} else {
			if($item->status == 'draft') {
				$item->time = time();
			}
			$item->status = 'live';
		}

		$this->data->save($item);
		return true;
	}


	public function delete($id)
	{
		$item = $this->data->finder('Live\Entity')->one($id);

		if(! $item) {
			$this->error = 'Post not found';
			return;
		}

		$this->data->delete($item);
		return true;
	}


	public function manage($articleId, $userId)
	{
		$items = $this->data->finder('Live\Entity')
		->select('user.username')
		->join('user', 'left')
		->on('user.id', 'live.user_id')
		->where('article_id', $articleId)
		->order('time', 'desc')
		->all();

		$results = [];
		foreach($items as $item) {
			if($item->status == 'live' ||  $item->user_id == $userId){
				$results[] = [
					'id' => $item->id,
					'delete' => $userId == $item->user_id,
					'update' => $userId == $item->user_id,
					'status' => $item->status,
					'body' => $item->body,
					'time' => $item->time,
					'username' => $item->username,
				];
			}
		}
		return $results;
	}


	public function id($id)
	{
		return $this->data->finder('Live\Entity')
		->one($id);
	}

}