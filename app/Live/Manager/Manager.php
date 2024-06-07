<?php

namespace Live\Manager;

use Sulfur\Manager\Manager as BaseManager;

use Live\Model;
use Article\Model as Article;

class Manager extends BaseManager
{


	public function __construct(Model $model, Article $article)
	{
		$this->model = $model;
		$this->article = $article;
	}


	public function manage($id)
	{
		$article = $this->article->id($id);
		if($article) {
			return $this->payload('live/manage', [
				'id' => $id,
				'title' => $article->title
			]);
		}
	}


	public function createpost($id)
	{
		$success = false;
		$message = '';
		if($this->model->create($this->request->post(), $id, $this->identity->id)) {
			$success = true;
		} else {
			$message = $this->model->error();
		}
		return $this->payload('json', [
			'data' => [
				'success' => $success,
				'message' => $message,
			]
		]);
	}


	public function updatepost($id)
	{
		$success = false;
		$message = '';
		if($this->model->update($id, $this->request->post())) {
			$success = true;
		} else {
			$message = $this->model->error();
		}
		return $this->payload('json', [
			'data' => [
				'success' => $success,
				'message' => $message,
			]
		]);
	}

	public function deletepost($id)
	{
		$success = false;
		$message = '';
		if($this->model->delete($id)) {
			$success = true;
		} else {
			$message = $this->model->error();
		}
		return $this->payload('json', [
			'data' => [
				'success' => $success,
				'message' => $message,
			]
		]);
	}


	public function posts($id)
	{
		return $this->payload('json', [
			'data' => $this->model->manage($id, $this->identity->id),
		]);
	}
}