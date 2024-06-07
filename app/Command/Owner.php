<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Data;

class Owner extends Command
{

	public function __construct(Data $data)
	{
		$this->data = $data;
	}


	public function handle()
    {
		$from = 0;
		$size = 1000;
		$total = 80000;
		for($i = 0; $i < $total; $i += $size) {
			$articles = $this->data->database()
			->select('id', 'body')
			->from('article')
			->order('id', 'desc')
			->offset($i)
			->limit($size)
			->iterator();

			foreach($articles as $article) {
				$body = json_decode($article['body'], true);
				$body = $this->unsetOwner($body);

				$this->data->database()
				->update('article')
				->set([
					'body' => json_encode($body, JSON_UNESCAPED_UNICODE)
				])
				->where('id', $article['id'])
				->execute();
				exit;
			}
		}
	}


	protected function unsetOwner($data) {
		if(is_array($data)) {
			if(isset($data['owner'])) {
				unset($data['owner']);
			}
			if(isset($data['lock'])) {
				unset($data['lock']);
			}
			foreach($data as $key => $value) {
				$data[$key] =$this->unsetOwner($value);
			}
		}

		return $data;
	}
}



