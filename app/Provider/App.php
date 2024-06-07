<?php

namespace Provider;

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

class App
{
	public static function register($container)
	{

		$config =  $container->get('Sulfur\Config');

		$container->set([
			'Sulfur\Data\Migration' => [':paths' => $config->migration('entities')],
			'Sphinx\Client' => function() use ($config) {
				$sphinx = new \Sphinx\Client();
				$sphinx->SetServer($config->sphinx('host'), $config->sphinx('port'));
				return $sphinx;
			},
			'Sulfur\Cache' => [
				'Sulfur\Cache\Factory::make',
				'config' => [$config, ':resource' => 'cache'],
				':name' => $config->env('env') == 'development' ? 'file' : 'default'
			],

			'Command\Sitemap' => ['filesystem' => [':name' => 'sitemap']],

			'Command\Newsmap' => ['filesystem' => [':name' => 'sitemap']],

			'Command\Images' => ['filesystem' => [':name' => 'images']],

			'Image\Controller@preset' => ['filesystem' => [':name' => 'cache']],

			'Image\Controller@preset_archive' => [
				'image' => [
					'origin' => ['Sulfur\Filesystem', 'name'=> [$config, ':resource' => 'image_archive', ':key' => 'filesystem.origin', ':default' => 'default' ]],
					'cache' => ['Sulfur\Filesystem', 'name'=> [$config, ':resource' => 'image_archive', ':key' => 'filesystem.cache', ':default' => 'default' ]],
					'config' => [$config, ':resource' => 'image_archive'],
				],
				'filesystem' => [':name' => 'cache_archive']
			],

			'Article\Manager\Og' => [
				'images' => [':name' => 'images'],
				'cache' => [':name' => 'cache'],
				'resources' => [':name' => 'resources'],
			],

			'Api' => ['config' => function() use ($config) {
				return $config->api();
			}],

			// 'Newsletter\Model' => [
			// 	'sendpulse' => function() use ($config) {
			// 		return new ApiClient(
			// 			$config->newsletter('user'),
			// 			$config->newsletter('secret'),
			// 			new FileStorage($config->newsletter('path'))
			// 		);
			// 	},
			// 	':config' => $config->newsletter()
			// ],

			'Contribute\Mollie' => function() use ($container) {
				$config =  $container->get('Sulfur\Config')->mollie();
				$client = new \Mollie\Api\MollieApiClient();
				$client->setApiKey($config['key']);
				return new \Contribute\Mollie(
					$client,
					$container->get('Sulfur\Session'),
					$container->get('Sulfur\Email'),
					$container->get('Newsletter\Mailchimp'),
					$container->get('Account\Mollie'),
					$container->get('Account\Model'),
					$container->get('Url'),
					$container->get('Message\Email')
				);
			},

			'Newsletter\Mailchimp' => [
				'client' => function() use ($config) {
					$client = new \DrewM\MailChimp\MailChimp($config->mailchimp('key'));
					return $client;
				},
				':list' => $config->mailchimp('list')
			],


			'Newsletter\Model' => [
				'mailchimpBell' => [
					'Newsletter\Mailchimp',
					'client' => function() use ($config) {
						$client = new \DrewM\MailChimp\MailChimp($config->mailchimp('bell.key'));
						return $client;
					},
					':list' => $config->mailchimp('bell.list')
				]
			],


			'Lang' => ['paths' => function() use ($config) {
				return $config->lang('paths');
			}],

			'Middleware\Notfound' => ['logger' => [':name' => 'notfound']],

			'Account\Jwt' => ['config' => function() use ($config) {
				return $config->jwt();
			}],

			'Account\Encrypt'=> ['config' => function() use ($config) {
				return $config->encrypt();
			}],

			'Account\Mollie' => ['client' => function() use ($container) {
				$config = $container->get('Sulfur\Config')->mollie();
				$client = new \Mollie\Api\MollieApiClient();
				$client->setApiKey($config['key']);
				return $client;
			}],

			'Account\Mailchimp' => [
				'client' => function() use ($config) {
					$client = new \DrewM\MailChimp\MailChimp($config->mailchimp('key'));
					return $client;
				},
				':list' => $config->mailchimp('list')
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

			'Message\Email' => ['config' => function() use ($config){
				return $config->message('email');
			}]
		]);

		$container->share([
			'Lang',
			'Account\Identity'
		]);
	}


}