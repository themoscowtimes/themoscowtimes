<?php

namespace TelegramApi;

use Article\Model as Article;

use Block;
use fetch;
use Url;

class Model {

	public function __construct(
		Article $article,
		Url $url
	)
	{
		$this->article = $article;
		$this->url = $url;
	}


	public function articles($filters = [], $limit = 9999, $offset = 0)
	{
		$articles = [];
		foreach($this->article->export($filters, $limit,  $offset) as $article){
			$sections = [];
			foreach(['news', 'opinion', 'business', 'city', 'ukraine_war', 'diaspora', 'lecture_series'] as $section) {
				if($article->{$section} == 1) {
					$sections[] = $section;
				}
			}

			if($article->image) {
				$mainImage = fetch::src($article->image, 'article_960');
			} else {
				$mainImage = null;
			}

			if($article->type == 'video') {
				$video = $article->video;
			} else {
				$video = null;
			}

			if($article->type == 'gallery') {
				$gallery = [];
				foreach($article->images as $image) {
					$gallery[] = [
						'image' => fetch::src($image, 'article_960'),
						'title' => $image->junction('title'),
						'caption' => $image->junction('caption'),
						'credits' => $image->junction('credits'),
					];
				}
			} else {
				$gallery = null;
			}


			// pull out podcasts
			$podcast = null;
			if($article->type == 'podcast') {
				foreach($article->body as $block) {
					if($block['type'] == 'embed' && isset($block['embed'])) {
						if(preg_match('#simplecast\.com\/([^\?]+)#', $block['embed'], $matches)) {
							$podcast = [
								'id' => $matches[1],
								'audio' => 'https://audio.simplecast.com/' . $matches[1] . '.mp3'
							];
						}
					}
				}
			}

			$body = [];
			foreach($article->body as $block) {
				$block = new Block($block);
				$prepared = [
					'type' => $block->type()
				];
				switch($block->type()) {
					case 'article':
						$prepared['id'] = (int) $block->article->id();
						break;
					case 'html':
					case 'aside':
						$content =  $block->body();
						/*
						// replace ending paragraphs and br's with newline
						$content = str_ireplace('/p>', "/p>\n", $content);
						$content = str_ireplace(['br/>', 'br />', 'br>'], "br>\n", $content);
						// strip all tags
						$content = strip_tags($content);
						// remove more than two newlines
						$content = preg_replace('#(\n\s*){3,}#m',"\n\n", $content);
						 */
						$prepared['content'] = $content;
						break;
					case 'embed':
						$prepared['content'] = $block->embed();
						break;
					case 'header':
						$prepared['content'] = $block->title();
						break;
					case 'image':
						$prepared['image'] = fetch::src($block->image(), 'article_960');
						$prepared['caption'] = $block->image->junction->caption();
						$prepared['credits'] = $block->image->junction->credtis();
						break;
					case 'images':
						$images = [];
						if(is_array($block->images())) {
							foreach($block->images() as $image) {
								$image = new Block($image);
								$images[] =  [
									'image' => fetch::src($image, 'article_960'),
									'caption' => $image->junction->caption(),
									'credits' =>  $image->junction->credits(),
								];
							}
						}
						$prepared['images'] = $images;
						break;
					case 'link':
						$prepared['url'] = $block->link->url;
						$prepared['title'] = $block->link->title;
						break;
					case 'quote':
						$prepared['quote'] = $block->body();
						$prepared['name'] = $block->by();
						break;
				}
				$body[] = $prepared;
			}

			$articles[] = [
				'id' => $article->id,
				'url' => $this->url->route('article', $article->data()),
				'type' => $article->type,

				'created' => $article->created,

				'image' => $mainImage,
				'caption' => $article->caption,
				'credits' => $article->credits,

				'video' => $video,
				'gallery' => $gallery,
				'podcast' => $podcast,

				'title' => $article->title,
				'body' => $body,

			];
		}
		return $articles;
	}

}
