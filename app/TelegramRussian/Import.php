<?php

	namespace TelegramRussian;

	use Sulfur\Data;
	use TelegramBot;

	class Import
	{
		protected $data = null;
		protected $russian_bot = null;

		public function __construct(
			Data $data,
			TelegramBot $russian_bot
		) {
			$this->data = $data;
			$this->russian_bot = $russian_bot;
		}

		public function import()
		{
			// https://crontab.guru/#*_8-17_*_*_1-5 or 
			// https://crontab.guru/#*_8-17_*_*_*
			/**
			 * getUpdates method
			 * https://telegram-bot-sdk.readme.io/reference/getupdates
			 */
			$results = $this->russian_bot->getRuMessages(
				[
					'offset' => '-1',
					'allowed_updates' => ['channel_post']
				]
			);
			return $this->getPost($results);
		}

		public function getPost($results)
		{
			if (isset($results) && !empty($results)) {
				$post = [];
				foreach($results as $result) {
					$post = [
						'title_ru' => $result[0],
						'title' => $result[1],
						'telegram_post_id' => $result[2],
						'created' => date('Y-m-d H:i:s', $result[3]),
						'zone' => 'main'
					];
				}

				$postId = $this->data->database()
				->select('id')
				->from('telegram_russian')
				->where('telegram_post_id', $post['telegram_post_id'])
				->limit(1)
				->result(null, 'id')[0] ?? null;

				if ($postId) {
					// var_dump($postId);
				} else {
					// var_dump($post);
					$this->data->database()
					->insert('telegram_russian')
					->values($post)
					->result();
				}
			}
		}
	}
?>
