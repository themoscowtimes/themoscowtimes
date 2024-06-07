<?php


require __DIR__ . '/../vendor/autoload.php';

$builds = [
	'main' => [
		'in' => __DIR__ . '/../resources/less/main.less',
		'out' => __DIR__ . '/css/main.css',
	],
	'manager' => [
		'in' => __DIR__ . '/../vendor/sulfur/manager/resources/less/main.less',
		'out' => __DIR__ . '/css/manager.css',
	]
];

$build = isset($_GET['build']) ? $_GET['build'] : 'main';

if(isset($builds[$build])) {
	$parser = new Less_Parser([
		'compress' => true,
		'sourceMap'	=> true,
		'sourceMapWriteTo' => 'css/' . $build . '.map',
		'sourceMapURL'      => $build . '.map'
	]);

	$parser->parseFile( $builds[$build]['in'], '');
	$css = $parser->getCss();

	file_put_contents($builds[$build]['out'], $css);

	header('Content-type: text/css', true);
	echo $css;
}