<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Config;

use Sulfur\Filesystem;
use Sulfur\Database;


class Events extends Command
{

	protected $arguments = [];


	protected $config;

	/**
	 *
	 * @var \Sulfur\Filesystem
	 */
	protected $filesystem;

	/**
	 *
	 * @var Sulfur\Database
	 */
	protected $database;


	/**
	 * Creation command
	 * @param DataMigration $migration
	 * @param \Sulfur\Config $config
	 */
	public function __construct(Config $config, Filesystem $filesystem, Database $database)
	{
		$this->config = $config;
		$this->filesystem = $filesystem;
		$this->database = $database;
	}


	/**
	 * Handle the command
	 */
	public function handle()
    {

		//$tags = $this->tags();
		//var_dump($tags);
		//return;

		$events = $this->events(1000);

		/*
		$tags = [
			'5c754050ac1621001bc040e2' => 'Carnaval',
			'5ae48caf5c4e5b0010bfe18c' => 'Masterclass',
			'5bf3d301fbfa7e2810341f46' => 'The best',
			'5c754050ac1621001bc040e2' => 'Carnaval',
			'5c754050ac1621001bc040e2' => 'Carnaval',
			'5c754050ac1621001bc040e2' => 'Carnaval',
			'5c754050ac1621001bc040e2' => 'Carnaval',
		];
		 */


		$result = [];
		$places = [];
		foreach($events['items'] as $event) {

			$event = $this->event($event['id'])['item'];

			if(isset($event['images']) && is_array($event['images']) && count($event['images']) > 0) {
				$event['image'] = $event['images'][0]['image'];
			}

			$event['description'] = nl2br($event['description']);
			$event['places'] = [];
			if(isset($event['places_ids']) && is_array($event['places_ids']) ) {
				foreach($event['places_ids'] as $placeId) {
					// cache places
					if(! isset($places[$placeId])) {
						$place = $this->place($placeId);

						if(isset($place['item'])) {
							$place = $place['item'];
							if(isset($place['images']) && is_array($place['images']) && count($place['images']) > 0) {
								$place['image'] = $place['images'][0]['image'];
							}

							unset($place['images']);
							unset($place['tags_ids']);
							unset($place['metro_ids']);
							unset($place['squeeze_ids']);
							unset($place['is_favorite']);

							$places[$placeId] = $place;
						} else {
							$places[$placeId] = false;
						}
					}


					if($places[$placeId] ) {
						$event['places'][] = $places[$placeId];
					}
				}
			}


			$schedules = $this->schedule($event['id']);
			$dates = [];

			foreach($schedules as $schedule) {
				foreach($schedule['events'] as $startEnd) {
					$dates[] = [
						'start' => $startEnd['start'],
						'end' => $startEnd['end']
					];
				}
			}


			$event['dates'] = $dates;

			unset($event['images']);
			unset($event['tags_ids']);
			unset($event['small_schedule']);
			unset($event['places_ids']);
			//unset($event['event_types_ids']);
			unset($event['is_favorite']);

			$result[] = $event;
		}


		$this->filesystem->put('events/all.json', json_encode($result, JSON_PRETTY_PRINT));




		//var_dump($result);


		//file_put_contents('events.txt', $output);
		//echo $output;

		//var_dump($events['items']);

		//var_dump($this->event('5a9e4a0ed898080010f774bb'));
		/*
		'v1/screens/events'

		$curl = curl_init();

		// $url = 'https://mf-dev.technolab.com.ru/v1/screens/events?language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/event?id=5ad73c47b127f8001036b9c8&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/schedule?id=5bd03daa933b90002344ce35&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/tags?language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/place?id=5be95317acf03b002322c755&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/place?id=5a9e4a0ed898080010f774bb&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/place?id=5be95317acf03b002322c755&language=en';




		curl_setopt($curl, CURLOPT_URL, $url );

		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'x-app-token: fd31f6e0-ddd7-44c4-bac2-3095a47ff1eb',
		]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);

		// var_dump(curl_error($curl));
		$arr = json_decode($result, true);
		//var_dump($arr['items']);
		var_dump($arr);
        curl_close($curl);
		 */
	}



	protected function tags()
	{
		return $this->get('screens/tags?language=en');
	}


	protected function events($amount = 10)
	{
		return $this->get('screens/events?language=en&pageSize=' . $amount);
	}


	protected function event($id)
	{
		return $this->get('screens/event?id=' . $id . '&language=en');
	}

	protected function place($id)
	{
		return $this->get('screens/place?id=' . $id . '&language=en');
	}

	protected function schedule($id)
	{
		return $this->get('schedule?id=' . $id . '&language=en');
	}


	protected function get($url)
	{
		$curl = curl_init();

		// $url = 'https://mf-dev.technolab.com.ru/v1/screens/events?language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/event?id=5ad73c47b127f8001036b9c8&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/schedule?id=5bd03daa933b90002344ce35&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/tags?language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/place?id=5be95317acf03b002322c755&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/place?id=5a9e4a0ed898080010f774bb&language=en';
		//$url = 'https://mf-dev.technolab.com.ru/v1/screens/place?id=5be95317acf03b002322c755&language=en';

		curl_setopt($curl, CURLOPT_URL, 'https://mf-dev.technolab.com.ru/v1/' . $url );
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'x-app-token: fd31f6e0-ddd7-44c4-bac2-3095a47ff1eb',
		]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
		//var_dump(curl_error($curl));
		$arr = json_decode($result, true);
        curl_close($curl);
		return $arr;
	}
}

