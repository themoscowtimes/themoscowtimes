<?php

use Sulfur\Cache;

class Bites
{
	public function __construct(Cache $cache)
	{
		$this->url = 'https://wp.bites.themoscowtimes.com/wp-json/wp/v2/posts';
		$this->cache = $cache;
	}



	public function data()
	{
		$feed = $this->cache->get('bites.feed');
		if(! $feed) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER , 1);
			$feed = json_decode(curl_exec($curl));
			if(! is_array($feed)) {
				return false;
			}
			$this->cache->set('bites.feed', $feed, 3600);
		}

		if(! is_array($feed) || ! isset($feed[0])) {
			return false;
		}

		$latest = $feed[0];

		$content = ($latest->content->rendered) ?? '';
		$content = str_ireplace('/pre>', "/pre>##br####br##", $content);
		$content = str_ireplace(['<br/>', '<br />', '<br>'], "##br##", $content);
		$content = strip_tags($content);
		$content = str_ireplace('##br##', "<br>", $content);

		return [
			'content' => $content,
			'date' =>  $latest->date,
			'link' => 'https://bites.themoscowtimes.com/',
			'title' => $latest->title->rendered
		];
	}
}