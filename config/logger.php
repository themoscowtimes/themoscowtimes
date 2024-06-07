<?php return [
	'fail' => [
		Sulfur\Logger\Logger::HANDLER_FILE => [
			'active' => true,
			'level' => Sulfur\Logger\Logger::LEVEL_DEBUG,
			'format' => Sulfur\Logger\Logger::FORMAT_LINE,
			'path' => '{{logs.path.fail}}'
		],
		Sulfur\Logger\Logger::HANDLER_SYSLOG => [
			'active' => false,
			'level' => Sulfur\Logger\Logger::LEVEL_WARNING,
			'format' => Sulfur\Logger\Logger::FORMAT_LINE,
			'id' => 'application',
		],
		Sulfur\Logger\Logger::HANDLER_MAIL => [
			'active' => false,
			'level' => Sulfur\Logger\Logger::LEVEL_EMERGENCY,
			'format' => Sulfur\Logger\Logger::FORMAT_HTML,
			'to' => 'martijn@yuna.nl',
			'from' => 'fail@yuna.nl',
			'subject' => 'Exception encountered',
		],
	],
	'notfound' => [
		Sulfur\Logger\Logger::HANDLER_FILE => [
			'active' => true,
			'level' => Sulfur\Logger\Logger::LEVEL_DEBUG,
			'format' => Sulfur\Logger\Logger::FORMAT_LINE,
			'path' => '{{logs.path.notfound}}'
		],
	],
];