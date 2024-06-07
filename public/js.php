<?php

/**
 * Possible build files
 */
$builds = [
	'main' => __DIR__ . '/js/main.js',
	'manager' => __DIR__ . '/js/manager.js'
];

/**
 * Paths to get files from
 */
$paths = [
	__DIR__ . '/vendor/',
	__DIR__ . '/../resources/js/',
	__DIR__ . '/../vendor/sulfur/manager/resources/js/',
];


/**
 * File to load
 */
$file = isset($_GET['file']) ? sanatize($_GET['file']) : false;


/**
 * Get the requested script
 * Add it to the build if a build was provided
 * Output the script
 */
if ($file) {
	// get build from qs
	$build = isset($_GET['build']) ? $_GET['build'] : false;

	if ( isset($builds[$build])) {
		$build = $builds[$build];
	} else {
		$build = false;
	}

	// go through paths to find the script
	foreach ($paths as $path) {
		if (check($path . $file, $path)) {
			// get the script
			$script = file_get_contents($path . $file);

			// add it to the build and build file
			if($build){
				add_script($script, $file, $build);
			}
			output_script($script);
			exit;
		}
	}
}

/**
 * Print to console on the front end
 */
function console_log($output)
{
	$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
	echo $js_code;
}


/**
 * Rebuild the build
 */
$build = isset($_GET['rebuild']) ? $_GET['rebuild'] : false;

if ( isset($builds[$build])) {
	$build = $builds[$build];
	$rebuild = rebuild($build, $paths);
	output_script($rebuild);
	exit;
}

/**
 * Minify JS
 */
function minify($script)
{
	return $script;
}


/**
 * Add a script to the build
 * @param string $script
 * @param file $file
 * @param string $build
 */
function add_script($script, $file, $build)
{
	$handle = fopen($build , 'a+');
	flock($handle, LOCK_EX);
	$contents = stream_get_contents($handle);
	$marker = '//_____ '.$file.' _____//';
	if(strpos($contents, $marker) === false){
		$contents = $contents . "\n\n" . $marker . "\n\n" . $script;
		ftruncate($handle, 0);
		fwrite($handle, $contents);
		fflush($handle);
	}
	flock($handle, LOCK_UN);
	fclose($handle);
}


/**
 * Rebuild the build by getting all the paths and including them again
 * @param string $build
 * @param array $paths
 * @return string
 */
function rebuild($build, $paths)
{
	$handle = fopen($build , 'a+');
	flock($handle, LOCK_EX);

	$contents = stream_get_contents($handle);
	$rebuild = '';
	$added = [];
	preg_match_all('#\/\/_____\s([^\s]+)\s_____\/\/#', $contents, $files);

	foreach($files[1] as $file){
		foreach ($paths as $path) {
			if (check($path . $file, $path) && ! in_array($file, $added)) {
				$script = file_get_contents($path . $file);
				$marker = '//_____ '.$file.' _____//';
				$rebuild = $rebuild . "\n\n" . $marker . "\n\n" . $script;
				$added[] = $file;
				break;
			}
		}
	}
	ftruncate($handle, 0);
	fwrite($handle, $rebuild);
	fflush($handle);
	flock($handle, LOCK_UN);
	fclose($handle);
	return $rebuild;
}


/**
 * Send script to the browser
 * @param type $script
 */
function output_script($script)
{
	header("Content-type: application/javascript");
	echo $script;
}


/**
 * Clean up filename
 * @param string $file
 * @return string
 */
function sanatize($file)
{
	// remove .js
	$file = str_replace('.js', '', $file);
	// remove all other characters
	$file = str_replace('#[^a-zA-Z0-9_-/]#', '', $file);
	// strip more than one slash
	$file = str_replace('#/{2,}#', '/', $file);
	// append .js
	return $file . '.js';
}


/**
 * Make sure the requested file isn't higher than this file to prevent hacking attempts.
 * Also check if exists
 * @param string $path
 * @return boolean
 */
function check($path, $highest)
{
	if(strpos(realpath($path), realpath($highest)) === false) {
		return false;
	}
	return file_exists($path);
}