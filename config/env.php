<?php return [
	'env' => 'development',
	'version' => 5,
	'cookie.salt' => 'cookiesalt',
	'cookie.secure' => false,
	'mysql.host' => 'localhost',
	'mysql.username' => '',
	'mysql.password' => '',
	'mysql.database' => '',
	'mysql.log' => false,
	'sphinx.host' => 'host878.procolix.com',
	'sphinx.port' => '9312',
	'session.storage.type' => Sulfur\Session::STORAGE_NATIVE,
	'session.storage.type' => Sulfur\Session::STORAGE_DATABASE,
	'filesystem.default' => ROOT . 'storage/',
	'filesystem.files' => ROOT . 'public/files/',
	'filesystem.images' => ROOT . 'storage/images/',
	'filesystem.images_archive' => ROOT . 'storage/images_archive/',
  'filesystem.static' => ROOT . 'public/',
  'filesystem.resources' => ROOT . 'resources/',
	'logs.path.fail' => ROOT . 'storage/logs/fail',
	'logs.path.notfound' => ROOT . 'storage/logs/notfound',
	'url.static' => '/public/',
	'url.base' => '/public/',
	'url.telegramapi' => '/public/telegram/articles',
	'framework.cache.active' => false,
	'framework.cache.path' => ROOT . 'storage/cache/framework/',
	// 'fail.handler' => Sulfur\Middleware\Fail::HANDLER_DEBUG,
	'fail.handler' => Sulfur\Middleware\Fail::HANDLER_PAGE,
	'cache.active' => false,
	'cache.host' => 'localhost',
	'cache.auth' => false,
	'cache.path' => ROOT . 'storage/cache/',
	'newsletter.user' => '',
	'newsletter.secret' => '',
	'newsletter.id' => '',
	'newsletter.business_id' => '',
	'newsletter.path' => ROOT . 'storage/newsletter/',
	'mollie.key' => 'test_jRkMD7fuFKjBHqzp5TxUmMEn9ahgw2',
	'mollie.mode' => 'test',
	'mailchimp.key' => '2544a3e93acad88a687951d46b224afd-us10',
	'mailchimp.list' => 'dd76937a10',
	'mailchimp.bell.key' => 'c9de002ca89a826997f11c1661114ecd-us10',
	'mailchimp.bell.list' => 'ad1d63f473',
	'api.token.telegram' => 'b29e70b59d2ce62824f8d564b50d0c',
  // Telegram Bot
  'telegram.bot_token' => '6104440683:AAEZEfseEfwMfOGrosXFjsfBvPPicBAetxE',
	'telegram.bot_token_dec_2023' => '6914486861:AAGmhhmmtP0RmyNSbD-e__fKqtPnBYUYgDI',
  'telegram.chat_id' => '@moscowtimes_en',
	// Russian Telegram bot
	'telegram.russian_bot_token' => '6248819476:AAF7pT_m_rbCvAed_JzmWDDE-YZQPvBl4vQ',
	'email.transport' => Sulfur\Email::TRANSPORT_SENDMAIL,
	'email.host' => '',
	'email.port' => '',
	'email.username' => '',
	'email.password' => '',
	'email.encryption' => 'ssl',
	'jwt.key' => '',
	'encrypt.path' => ROOT . '',
	'encrypt.key' => '',
	'encrypt.iv' => 1234567890123456,
	'message.email.domain' => 'yunademo.nl',
	'php.display_errors' => 1,
];
