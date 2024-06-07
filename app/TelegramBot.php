<?php

use Telegram\Bot\Api as Bot;
use Telegram\Bot\FileUpload\InputFile;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TelegramBot
{

	protected $bot;
	protected $russian_bot;
	protected $chat_id;
	protected $russian_chat_id;
	protected $url;

	public function __construct(
		Bot $bot,
		Bot $russian_bot,
		$chat_id,
		$russian_chat_id,
		Url $url)
	{
		$this->bot = $bot;
		$this->russian_bot = $russian_bot;

		$this->chat_id = $chat_id;
		$this->russian_chat_id = $russian_chat_id;

		$this->url = $url;
	}

	public function paragraph($body)
	{
		$replaced = str_replace(['<p>', '</p>'], ['', '\n'], $body);
		$stripped = strip_tags(html_entity_decode($replaced), '<a>');
		return $stripped;
	}

	public function sendMessage($article)
	{
		if ($article->image) {
			$image_url = $this->url->route('image', [
				'preset' => 640,
				'path' => trim($article->image->path, '/'),
				'file' => $article->image->file
			]);

			$link = $this->url->route('article', $article->data());
			$bot = $this->bot;
			$chat_id = $this->chat_id;
			$body = '';

			if ($article->body[0]['type'] == 'html') {
				$body = $article->body[0]['body'];
			}

			// Telegram limits caption field chars. Limit to 3 paragraphs.
			$body_arr = explode('\n', $this->paragraph($body));
			$limit_arr = array_slice($body_arr, 0, 2);
      // If article type is gallery, use intro text
      $paragraphs = ($article->type == 'gallery' ?  $article->intro : implode(PHP_EOL, $limit_arr));
			// Cover image
			$image = new InputFile($image_url);
			// Labels
			$label = '';
			if ($article->type == 'gallery') {
				$label = '<em>Gallery</em> | ';
			} elseif ($article->opinion) {
				$label = '<em>Opinion</em> | ';
			} elseif ($article->indepth) {
				$label = '<em>Feature</em> | ';
			} elseif ($article->city) {
				$label = '<em>Arts and Life</em> | ';
			}
			// Author
			$authors_arr = [];
			$author_html = '';
			$separator = 'By ';
			if (isset($article->authors) && is_array($article->authors)) {
				foreach ($article->authors as $author) {
					$authors_arr[] = $author->title;
				}
			}
			while ($author_name = array_shift($authors_arr)) {
				$author_html .= '<em>' . $separator . $author_name . '</em>';
				$separator = ' and ';
			}
			$is_author = empty($author_html) ? '' : PHP_EOL . $author_html;
			$author = ($article->opinion || $article->city) ? $is_author : '';
			// Opinion disclaimer
			$disclaimer = $article->opinion ? '<em>The views expressed in opinion pieces do not necessarily reflect the position of The Moscow Times.</em>' . PHP_EOL . PHP_EOL : '';

			$data = [
				'chat_id' => $chat_id,
				'photo' => $image,
				'caption' =>
					$label . '<b>' . $article->title .'</b>'
					. $author
					. PHP_EOL . PHP_EOL . $paragraphs . PHP_EOL . PHP_EOL . $disclaimer .
					'<a href="'. $link .'">' . ($article->type == 'gallery' ? 'View gallery' : 'Read more') . '</a> | <a href="https://t.me/+k2Mp9sAgVcIxMGQ6">Subscribe to our channel</a>',
				'parse_mode' => 'html',
				'disable_web_page_preview' => false
			];

			if (
        $article->type == "gallery"
        && $paragraphs != '' ||
        (
          $article->type == 'default'
          && ($article->news || $article->opinion || $article->city)
          && $paragraphs != ''
        )
			) {
				$bot->sendPhoto($data);
			}
		}
	}

	// Pull and translate posts from Russian Service Telegram
	public function translate($text = '', $from, $to)
	{
		$tr = new GoogleTranslate();
		return $tr->setSource($from)->setTarget($to)->translate($text);
	}

	public function getRuMessages($params = [])
	{

		$results = $this->russian_bot->getUpdates($params);

		$filtered = [];

		if(isset($results) && is_array($results) && !empty($results)) {
			foreach($results as $item) {
				/** If 'text' field is set, return 'text', else check for 'caption'
				* Split text by new line to get first line for title
				*
				* https://core.telegram.org/bots/api#getupdates param allowed_updates => [] not working 
				*/
				
				if (isset($item['channel_post'])) {
					$title = isset($item['channel_post']['text']) ?
					preg_split('/(\n\n)/', $item['channel_post']['text']) :
					(isset($item['channel_post']['caption']) ?
					preg_split('/(\n\n)/', $item['channel_post']['caption']) : null);

					$message_id = $item['channel_post']['message_id'];
					$date = $item['channel_post']['date'];

					$title_isset = is_array($title) ? $title[0] : $title;
					$translated_title = $this->translate($title_isset, 'ru', 'en');

					$filtered[] = [
						$title_isset,
						$translated_title,
						$message_id,
						$date
					];
				} elseif (isset($item['edited_channel_post'])) {
					$title = isset($item['edited_channel_post']['text']) ?
					preg_split('/(\n\n)/', $item['edited_channel_post']['text']) :
					(isset($item['edited_channel_post']['caption']) ?
					preg_split('/(\n\n)/', $item['edited_channel_post']['caption']) : null);

					$message_id = $item['edited_channel_post']['message_id'];
					$date = $item['edited_channel_post']['date'];

					$title_isset = is_array($title) ? $title[0] : $title;
					$translated_title = $this->translate($title_isset, 'ru', 'en');

					$filtered[] = [
						$title_isset,
						$translated_title,
						$message_id,
						$date
					];
				}
			}
		}
		return $filtered;
	}
	/**
	 * TODO: refactor to use webhook instead of getUpdates
	 */
	public function handleWebhook()
	{
		$result = $this->russian_bot->getWebhookUpdate();
		return $result;
	}
}
