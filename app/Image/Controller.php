<?php

namespace Image;

use Sulfur\Image;
use Sulfur\Filesystem;
use Exception;
use Sulfur\Config;
use Article\Manager\Og;
use Article\Model as Article;



class Controller
{
	public function preset(
		Image $image,
		Filesystem $filesystem,
		Config $config,
		Article $article,
		Og $og,
		$preset,
		$path,
		$file
	)
	{
		if(!array_key_exists($preset, $config->image('presets'))) {
			return;
		}

		if($cachePath = $this->cachePath($path, $file)) {

			if($preset == 'og') {
				$parts = explode('__', $file);
				if(count($parts) == 2 && (int) $parts[0] == $parts[0]) {
					if($item = $article->id($parts[0])) {
						if($item->image) {
							$bg = trim($item->image->path, '/') . '/' . $item->image->file;
						} else {
							$bg = null;
						}
						try{
							if($data = $og->image($item->id, $item->title, $bg)) {
								// serve data
								header('Content-Type: image/jpg');
								header('Content-Length:' . strlen($data));
								header('Cache-Control:max-age=31536000, public');
								header('Expires:' . date_create('+1 years')->format('D, d M Y H:i:s').' GMT');
								echo $data;
								exit;
							}
						} catch(Exception $e) {}

						// Still here, save 1x1 pixel
						$filesystem->copy('empty.jpg', $preset . '/' .$cachePath);
					}
				}
			} else {
				try {
					// try to create and serve image
					$image->serve($cachePath, $preset);
				} catch(Exception $e) {
					// if it fails, save a 1x1 pixel
					$filesystem->copy('empty.jpg', $preset . '/' .$cachePath);
				}
			}
		}
	}



	/**
	 * Alternative serve method, autowired with different configured objects
	 */
	public function preset_archive(Image $image, Filesystem $filesystem, Config $config, $preset, $path, $file)
	{

		if(!array_key_exists($preset, $config->image('presets'))) {
			return;
		}

		if($cachePath = $this->cachePath($path, $file)) {

			try {
				// try to create and serve image
				$image->serve($cachePath, $preset);
			} catch(Exception $e) {
									var_dump($e->getMessage());
		exit;
				// if it fails, save a 1x1 pixel
				$filesystem->copy('empty.jpg', $preset . '/' .$cachePath);
			}
		}
	}



	protected function cachePath($path, $file)
	{
		$path = preg_replace('#[^0-9a-zA-Z]#', '', $path);

		if(strlen($path) != 2) {
			return false;
		}

		$parts = pathinfo($file);

		if(! isset($parts['extension'])) {
			return false;
		}

		$extension = preg_replace('#[^a-zA-Z-]#', '',  $parts['extension']);

		if(strlen($extension) < 3) {
			return false;
		}

		$file = preg_replace('#[^0-9a-zA-Z-_\.\-\&]#', '', $parts['filename']);

		if(strlen($file) == 0) {
			return false;
		}

		$file = $file . '.' . $extension;

		$cachePath = $path == '' ? $file : ($path . '/' . $file);

		return $cachePath;
	}



}