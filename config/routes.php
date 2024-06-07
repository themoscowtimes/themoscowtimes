<?php return [

	'home' => ['', 'Home\Controller@index'],
	'homepreview' => ['preview/vir845hjbigr094ji', 'Home\Controller@preview'],

	// Account
	'register' => ['account/signup', 'Account\Controller@register'],
	'confirmation' => ['account/confirmation', 'Account\Controller@confirmation'],
	'confirm' => ['account/confirm/:token', 'Account\Controller@confirm'],
	'signin' => ['account/signin', 'Account\Controller@signin'],
	'signout' => ['account/signout', 'Account\Controller@signout'],
	'recover' => ['account/password', 'Account\Controller@recover'],
	'reset' => ['account/password/:token', 'Account\Controller@reset'],
	'terms' => ['account/terms', 'Account\Controller@terms'],
	'dashboard' => ['account', 'Account\Controller@dashboard', 'authenticated' => true],


	//'obfusciate' => ['account/obfusciate/:email', 'Account\Controller@obfusciate'],
	//'clarify' => ['account/clarify/:email', 'Account\Controller@clarify'],
	//'decrypt' => ['account/decrypt/:data', 'Account\Controller@decrypt'],

	// Telegam API
	'telegram_articles' => ['telegram/articles', 'TelegramApi\Controller@articles'],
	'telegram_webhook' => ['telegram/webhook', 'TelegramApi\Controller@handleWebhook'],

	'api_identity' => ['api/(:version)/identity', 'Account\Api@identity', 'version' => 1],
	'api_register' => ['api/(:version)/account/register', 'Account\Api@register', 'version' => 1],
	'api_confirmation' => ['api/(:version)/account/confirmation', 'Account\Api@confirmation', 'version' => 1],
	'api_customer' => ['api/(:version)/account/customer', 'Account\Api@customer', 'version' => 1],
	'api_confirm' => ['api/(:version)/account/confirm', 'Account\Api@confirm', 'version' => 1],
	'api_signin' => ['api/(:version)/account/signin', 'Account\Api@signin', 'version' => 1],
	'api_recover' => ['api/(:version)/account/recover', 'Account\Api@recover', 'version' => 1],
	'api_reset' => ['api/(:version)/account/reset', 'Account\Api@reset', 'version' => 1],

	'api_account' => ['api/(:version)/account/account', 'Account\Api@account', 'version' => 1, 'authenticated' => true],
	'api_donations' => ['api/(:version)/account/donations', 'Account\Api@donations', 'version' => 1, 'authenticated' => true],
	'api_signout' => ['api/(:version)/account/signout', 'Account\Api@signout', 'version' => 1, 'authenticated' => true],
	'api_signoff' => ['api/(:version)/account/signoff', 'Account\Api@signoff', 'version' => 1, 'authenticated' => true],
	'api_subscriptions' => ['api/(:version)/account/subscriptions', 'Account\Api@subscriptions', 'version' => 1, 'authenticated' => true],
	'api_update' => ['api/(:version)/account/update', 'Account\Api@update', 'version' => 1, 'authenticated' => true],
	'api_subscriptioncancel' => ['api/(:version)/account/cancelsubscription/:id', 'Account\Api@subscriptioncancel', 'version' => 1, 'authenticated' => true],
	'api_subscriptionupdate' => ['api/(:version)/account/updatesubscription/:id', 'Account\Api@subscriptionupdate', 'version' => 1, 'authenticated' => true],

	// articles
	'article' => [':year/:month/:day/:slug', 'Article\Controller@view', 'rules' => ['year' => '[0-9]{4}', 'month' => '[0-9]{2}', 'day' => '[0-9]{2}'], ],
	'articlepreview' => ['preview/v9trjvwoiw45ibfnhgftr/(:hash)', 'Article\Controller@preview'],
	'articlepdf' => [':year/:month/:day/:slug/pdf', 'Article\Pdf@articlepdf'],

	'opinion' => ['opinion/(:offset)', 'Article\Controller@section', 'section' => 'opinion'],
	'news' => ['news/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'news'],
	'meanwhile' => ['meanwhile/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'meanwhile'],
	'city' => ['arts-and-life/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'city'],
	'business' => ['business/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'business'],
	'climate' => ['climate/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'climate'],
	'diaspora' => ['diaspora/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'diaspora'],
	'indepth' => ['features/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'indepth'],
	'old_indepth' => ['in-depth/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'indepth'],
	'ukraine_war' => ['ukraine-war/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'ukraine_war'],
	'lecture_series' => ['lectures/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'lecture_series'],
	// 'sponsored' => ['sponsored/(:offset)', 'Article\Controller@section', 'rules' => ['offset' => '[0-9]+'], 'section' => 'sponsored'],


	'video' => ['videos/(:offset)', 'Article\Controller@type', 'rules' => ['offset' => '[0-9]+'], 'type' => 'video'],
	'gallery' => ['galleries/(:offset)', 'Article\Controller@type', 'rules' => ['offset' => '[0-9]+'], 'type' => 'gallery'],
	'podcasts' => ['podcasts', 'Article\Controller@podcasts'],

	'tag' => ['tag/:tag/(:offset)', 'Article\Controller@tag'],
	//'search' => ['search/:query/(:offset)', 'Search\Controller@view'],
	'search' => ['search', 'Search\Controller@index'],
	'apisearch' => ['api/search', 'Search\Controller@articles'],
	'apisearchauthors' => ['api/searchauthors', 'Search\Controller@authors'],

	'rss' => ['rss/:section', 'Article\Controller@rss', 'rules' => ['section' => '(news|opinion|city|meanwhile|podcasts|in-depth|arts-and-life)']],
	'feed' => ['feeds/partner.xml', 'Article\Controller@feed'],
	'yandexnews' => ['rss/yandex-news', 'Article\Controller@yandexnews'],
	'rss_telegram' => ['rss/telegram', 'Article\Controller@all', 'template' => 'rss_telegram'],
	'rss_all' => ['rss/all', 'Article\Controller@all', 'template' => 'rss_all'],

	// Liveblog
	'livepreview' => ['preview/dfgw45y467ikhner6yu5khjt', 'Live\Controller@preview'],
	'live' => ['live/:id/(:from)', 'Live\Controller@view'],

	// sponsored
	'campaign' => ['plus/:slug', 'Campaign\Controller@view'],
	'campaignpreview' => ['preview/95jkvif784ghd7udfj8ig', 'Campaign\Controller@preview'],
	'advertorial' => ['plus/:campaign/:slug', 'Advertorial\Controller@view'],
	'advertorialsvtimes' => ['plus/vtimes', 'Advertorial\Controller@vtimes'],
	'advertorialpreview' => ['preview/v945jbvfju89ui34dfbver', 'Advertorial\Controller@preview'],


	// archive
	'archive_article' =>  ['archive/:slug', 'Archive\Article\Controller@view'],
	'archive_author' =>  ['archive/author/:slug', 'Archive\Author\Controller@view'],
	//'archive' => ['archive/:year/:month/:day', 'Archive\Article\Controller@index', 'rules' => ['year' => '[0-9]{4}', 'month' => '[0-9]{2}', 'day' => '[0-9]{2}'], ],
	// archive leads to all-in-one search page
	'archive' => ['archive', 'Search\Controller@index'],
	'archivepreview' => ['preview/sdfgwe45ygn64riba4t4etjbvgnert76y/', 'Archive\Article\Controller@preview'],

	// ambassadors
	'ambassadors' => ['ambassadors', 'Ambassador\Controller@index'],
	'ambassador' => ['ambassador/:slug', 'Ambassador\Controller@view'],
	'ambassadorpreview' => ['preview/biwejjmguirth09w4bdfionhj8', 'Ambassador\Controller@preview'],

	// dossiers
	'dossier' => ['all-about/:slug/(:offset)', 'Dossier\Controller@view'],
	'dossierpreview' => ['preview/bvjkg895uvuv7fu4nnhcr45gdfcgb', 'Dossier\Controller@preview'],

	// newsletter
	'newsletter' => ['newsletter', 'Newsletter\Controller@signup'],
	'newsletterpreview' => ['newsletterpreview/:type', 'Newsletter\Controller@preview'],
	'newsletterform' => ['newsletter/signup', 'Newsletter\Controller@form'],
	'newslettersubmit' => ['newsletter/submit', 'Newsletter\Controller@submit'],
	'newslettermailchimptest' => ['mailchimp', 'Newsletter\Controller@mailchimp'],

	// newsletters landing
	'newsletters' => ['newsletters', 'Newsletter\Controller@index'],

	// authors
	'author' => ['author/:slug', 'Author\Controller@view',],
	'authors' => ['authors/:char', 'Author\Controller@authors'],

	// Partner
	'partner' => ['partner/:slug', 'Partner\Controller@view',],

	// events
	'events' => ['eventstest', 'Event\Controller@index'],
	'event' => ['event/:slug', 'Event\Controller@view',],
	'eventpreview' => ['preview/vr9t8hjyw9rtbjfpdiogsdrgf', 'Event\Controller@preview'],

	// issues
	'issues' => ['in-print/(:offset)', 'Issue\Controller@index'],
	'issue' => ['issue/:number', 'Issue\Controller@view'],
	'issuepreview' => ['preview/9niufnmce8u45jfvjv', 'Issue\Controller@preview'],

	// locations
	'locations' => ['locations', 'Location\Controller@index',],
	'location' => ['location/:slug', 'Location\Controller@view',],
	'locationpreview' => ['preview/v8bvgjurb347efhjchjdvj', 'Location\Controller@preview'],

	// pages
	'page' => ['page/:slug', 'Page\Controller@view'],
	'pagepreview' => ['preview/biwerth09w4bdfionhj9', 'Page\Controller@preview'],



	// contribute
	'contribute' => ['contribute', 'Contribute\Controller@view'],
	'contribute_submit' => ['contribute/submit', 'Contribute\Controller@submit'],
	'mollie_webhook' => ['mollie_webhook_90fgkv89fgu4u89bgfui', 'Contribute\Controller@webhook'],
	'mollie_recurringwebhook' => ['mollie_recurringwebhook_94ibivj589oxs4', 'Contribute\Controller@recurringwebhook'],
	'mollie_returned' => ['mollie_return_vj37xsl5nvec72jakioe', 'Contribute\Controller@returned'],

	// serve image
	'image' => ['image/:preset/:path/:file', 'Image\Controller@preset'],
	'image_archive' => ['image_archive/:preset/:path/:file', 'Image\Controller@preset_archive'],

	// build path to img
	'img' => ['img/:file', 'none'],

	// serve file
	'file' =>  ['files/:file', 'File\Controller@serve'],

	// redirects of old urls
	'redirect_article' => [':section/:slug', 'Article\Controller@redirect', 'rules' => ['section' => '(articles|photogalleries|news)']],
	'redirect_author' => ['authors/:id', 'Author\Controller@redirect',],
	'redirect_issues' => ['issues', 'Issue\Controller@redirect'],
	'redirect_issue' => ['issues/:number', 'Issue\Controller@redirect'],


	'redirect_archive' => ['(:part1)/(:part2)/(:part3)/:slug/:html', 'Archive\Article\Controller@redirect','rules' => ['html' => '[0-9]{4,}\.html']],

	// infinite scroll
	'scroll_load' => ['all/(:id)', 'Article\Controller@infiniteScrollItem'],

];