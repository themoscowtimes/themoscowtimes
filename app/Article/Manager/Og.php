<?php
namespace Article\Manager;

use Sulfur\Image;
use Sulfur\Filesystem;
use Intervention\Image\ImageManager;

class Og
{

	public function __construct(
		Filesystem $images,
		Filesystem $cache,
		Filesystem $resources
	)
	{
		$this->images = $images;
		$this->cache = $cache;
		$this->resources = $resources;

		// Filesystem config is private, but we need the actual absolute path for the fonts
		// Use this trick to access private props
		$root = '';
		$getRoot = function() use( & $root){
		   $root = $this->config['root'];
		};
		$getRoot->call($resources);

		$this->fontPath = $root. 'og/';
		$this->manager = new ImageManager(['driver' => 'gd']);
	}


	public function image($id, $title, $bg = null)
	{
		// Canvas settings
		$canvasWidth = 1200;
		$canvasHeight = 630;
		$backgroundColor = '#1b1b1c';
		$overlayFile = 'og/overlay.png';

		// Create he image
		$image = $this->manager->canvas($canvasWidth, $canvasHeight, $backgroundColor);

		// Try to add background
		if($bg) {
			if($this->images->has($bg)) {
				$bg = $this->manager->make($this->images->read($bg));
				$bg->fit($canvasWidth, $canvasHeight, function ($constraint) {
				}, 'center');
				// $bg->brightness(-40);
				$image->insert($bg, 'center');

				$overlay = $this->manager->make($this->resources->read($overlayFile));
				$overlay->fit($canvasWidth, $canvasHeight, function ($constraint) {
				}, 'center');

				$image->insert($overlay, 'center');

			}
		}



		// Logo Settings
		$logoWidth = 315;
		$logoFile = 'og/logo.png';

		// Add logo
		if($this->resources->has($logoFile)) {
			$logo = $this->manager->make($this->resources->read($logoFile));
			$logo->resize($logoWidth, null, function($resize) {
				$resize->aspectRatio(true);
			});
			$image->insert($logo, 'bottom', 0, 25);
		}


		// Get separated lines
		$lines = $this->lines($title, 30);

		// Settings for text
		$lineHeight = $bg ? 65 : 61;
		$fontFile = $bg ? $this->fontPath . 'heuristica-bold.otf' : $this->fontPath . 'heuristica.otf';
		$fontSize = $bg ? 58 : 55;
		$underline = $bg ? false : true;
		$underlineMargin = 20;
		$underlineHeight = 2;

		$top = (($canvasHeight - (count($lines) * $lineHeight)) / 2) + $fontSize - ($underline ? $underlineMargin : 0);
		$widest = 0;


		foreach($lines as $index => $line) {
			// Correction for h, p, hp
			$correction = $this->correction($line, $fontFile, $fontSize);

			$image->text($line, $canvasWidth / 2, $top + ($index * $lineHeight) + $correction, function($font) use ($fontFile, $fontSize, & $widest){
				$font->file($fontFile);
				$font->size($fontSize);
				$font->color('#fff');
				$font->align('center');
				$box = $font->getBoxSize();
				if($box['width'] > $widest) {
					$widest = $box['width'];
				}
			});
		}


		// Blue line
		if($underline) {
			$width = $widest;
			$x1 = ($canvasWidth - $width) / 2;
			$x2 = $x1 + $width;
			$y1 = $top + (count($lines) * $lineHeight) - $fontSize + $underlineMargin;
			$y2 = $y1 + $underlineHeight;
			$image->rectangle($x1,$y1,$x2,$y2, function($shape){
				$shape->background('#1348b0');
			});
		}

		$hash = md5($id);
		$path = substr($hash, 0, 2);
		$file = $id . '__' . $hash . '.jpg';
		$this->cache->put('og/' . $path . '/' . $file, $image->encode());
		// Return result
		return (string) $image->encode();
	}



	protected function correction($text, $fontFile, $fontSize)
	{
		static $corrections;
		static $test;
		if(is_null($test)) {
			$test = $this->manager->canvas(10, 10);
		}
		if(is_null($corrections)) {
			$corrections = [
				'a' => 0,
				'p' => 0,
				'hp' => 0,
			];
			foreach($corrections as $letters => $settings) {
				$test->text($letters, 0, 0, function($font) use ($fontFile, $fontSize , $letters, & $corrections){
					$font->file($fontFile);
					$font->size($fontSize);
					$box = $font->getBoxSize();
					$corrections[$letters] = $box['height'];
				});
			}
		}

		$corection = 0;
		$test->text($text, 0, 0, function($font) use ($fontFile, $fontSize, $corrections, & $correction){
			$font->file($fontFile);
			$font->size($fontSize);
			$box = $font->getBoxSize();
			if($box['height'] > $corrections['a'] + 4) {
				// We have a high line: h,p or hp
				if($box['height'] > $corrections['hp'] - 4) {
					// We have a very high line, it's hp type
					$correction = $corrections['p'] - $corrections['a'];
				} elseif($box[1] > 5) {
					// y is higher than zero, it means we have an p type
					$correction = $corrections['p'] - $corrections['a'];
				} else {
					// we have a h type, dont correct
					$correction = 0;
				}
			} else {
				$correction = 0;
			}
		});

		return $correction;

	}



	protected function lines($text, $lineLengthMax = 30)
	{
		// trim newlines
		$text = trim($text, "\n");

		// Check if there are newlines
		// Text was pre-layout
		if(strpos($text, "\n") !== false) {
			// Replace more than two newlines by two newlines
			// So no more the one empty line
			$text = preg_replace('#\n{2,}#', "\n\n", $text);
			// Get separate lines
			$lines = explode("\n", $text);
			// Check if too many lines
			if(count($lines) <= 7) {
				// Check if any line too long
				$lengthExceeded = false;
				foreach($lines as $line) {
					if( mb_strlen($line) > $lineLengthMax) {
						$lengthExceeded = true;
					}
				}
				// All good: return lines
				if(! $lengthExceeded) {
					return $lines;
				}
			}
		}


		// Still here
		// Text was not pre-layout, or pre-layout failed
		$textLength = mb_strlen($text);
		$lineLengthOptimal = round($textLength / (ceil($textLength / $lineLengthMax)));

		$lines = [];
		$line = '';
		$space = '';
		foreach(explode(' ', $text) as $word) {
			$word = trim($word);
			$wordLength = mb_strlen($word);

			if($wordLength > 0) {
				if(mb_strlen($line) + $wordLength > $lineLengthMax) {
					// Line length goes over the maximum
					// Store this line
					$lines[] = $line;
					// Start with the word on a new line
					$line = $word;
					// Proceed with a space
					$space = ' ';
				} elseif(mb_strlen($line) + $wordLength > $lineLengthOptimal) {
					// Line length goes over the optimal
					// Add the word to the line
					$line = $line . $space . $word;
					// Store this line
					$lines[] = $line;
					// Start with a new line
					$line = '';
					$space = '';
				} else {
					// Just add the word to the line
					$line = $line . $space . $word;
					$space = ' ';
				}
			}
		}
		// There's a pending line: add it
		if(mb_strlen($line) > 0) {
			$lines[] = $line;
		}

		return $lines;
	}
}