<?php return [
	'default' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.default}}'
	],
	'upload' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.upload}}'
	],
	'files' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.files}}'
	],
	'images' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.images}}'
	],
	'images_archive' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.images_archive}}'
	],
	'cache' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.static}}' . 'image'
	],
	'cache_archive' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.static}}' . 'image_archive'
	],
	'sitemap' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.static}}' . 'sitemap'
	],
	'resources' => [
		'storage' => Sulfur\Filesystem::STORAGE_LOCAL,
		'root' => '{{filesystem.resources}}'
	],
];