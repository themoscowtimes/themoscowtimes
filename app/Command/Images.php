<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Filesystem;
use Sulfur\Database;
use Sulfur\Image;



class Images extends Command
{

	/**
	 * @var \Sulfur\Filesystem
	 */
	protected $filesystem;

	/**
	 * Creation command
	 * @param DataMigration $migration
	 * @param \Sulfur\Config $config
	 */
	public function __construct(Filesystem $filesystem, Database $database, Image $image)
	{
		$this->filesystem = $filesystem;
		$this->database = $database;
		$this->image = $image;
	}


	/**
	 * Handle the command
	 */
	public function handle()
    {

		ini_set('memory_limit',  '400M' );


		$images = $this->database
		->select()
		->from('image')
		->where('width' , '>' , 2000)
		->orWhere('height' , '>' , 2000)
		->result();

		foreach($images as $image) {
			$path = trim($image['path'], '/') . '/' . $image['file'];
			$success = true;

			try{
				$img = $this->image->image($path);
				$img->resize(2000, 2000, function ($constraint) {
					$constraint->upsize();
					$constraint->aspectRatio();
				});

				$this->image->delete($path);
				$this->image->save($img, $path);
			} catch (Exception $e) {
				$success = false;
				$this->write('error');
			}

			if($success) {
				$query = $this->database
				->update('image')
				->set([
					'width' => $img->width(),
					'height' => $img->height(),
				])
				->where('id' , $image['id']);
				var_dump($query->compile());
				$query->execute();
			}

			break;
		}

		/*
		$dirs = $this->filesystem->contents();
		foreach($dirs as $dir) {
			if($dir['type'] == 'dir') {
				$files = $this->filesystem->contents($dir['path']);
				foreach($files as $file) {
					if($file['type'] == 'file' && $file['size'] > 1000000) {

						$this->write($file['size'] / 1000000 . ' Mb: ' . $file['path']);
					}
				}
			}
		}
		*/

	}
}