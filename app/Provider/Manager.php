<?php

namespace Provider;

class Manager
{
	public static function register($container)
	{
		$config =  $container->get('Sulfur\Config');
		$container->set([
			'Contribute\Mollie' => function() use ($container) {
				$config =  $container->get('Sulfur\Config')->mollie();
				$client = new \Mollie\Api\MollieApiClient();
				$client->setApiKey($config['key']);

				return new \Contribute\Mollie($client, $container->get('Sulfur\Session'), $container->get('Sulfur\Email'));
			},

			'Image\Manager\Manager' => [
				'upload' => ['filesystem' => [':name' => 'images']],
			],
				
			'Archive\Image\Manager\Manager' => [
				'upload' => ['filesystem' => [':name' => 'images_archive']],
				'filesystem' => [':name' => 'images_archive'],
				'image' => [
					'origin' => [':name' => 'images_archive'],
					'cache' => [':name' => 'cache_archive'],
					'config' => [$config, ':resource' => 'image_archive'],
				]
			],

			'Article\Manager\Og' => [
				'images' => [':name' => 'images'],
				'cache' => [':name' => 'cache'],
				'resources' => [':name' => 'resources'],
			],

			'TelegramBot' => [
				'bot' => function() use ($config) {
					$bot = new \Telegram\Bot\Api($config->telegrambot('bot_token'));
					return $bot;
				},
				'russian_bot' => function() use ($config) {
					$russian_bot = new \Telegram\Bot\Api($config->telegrambot('russian_bot_token'));
					return $russian_bot;
				},
				':chat_id' => $config->telegrambot('chat_id'),
				':russian_chat_id' => $config->telegrambot('russian_chat_id')
			],
			'Url' => [
				'url' => function() use ($container, $config){
					return $container->make('Sulfur\Url',[
						':router' => $container->make('Sulfur\Router', [':routes' => $config('app:routes')] ),
					]);
				}
			],

			// use different cache in a class:
			// 'Class\That\Uses\Cache' => [':cache' => [':name' => 'keyincacheconfig']]

			// use different log:
			// 'Class\That\Uses\Log' => [':logger' => [':name' => 'keyinloggerconfig']]

			// use different database:
			// 'Class\That\Uses\Database' => [':database' => [':name' => 'keyindatabaseconfig']]
		]);
	}
}