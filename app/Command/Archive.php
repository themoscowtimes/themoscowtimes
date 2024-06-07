<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Data;
use Sulfur\Config;
use Sulfur\Manager\Slug;

class Archive extends Command
{



	/**
	 * Creation command
	 * @param DataMigration $migration
	 * @param \Sulfur\Config $config
	 */
	public function __construct(Data $data, Config $config, Slug $slug)
	{
		$this->data = $data;
		$this->config = $config;
		$this->db = $this->data->database('old');
		$this->dest = $this->data->database('default');
		$this->slug = $slug;
	}


	/**
	 * Command description
	 * @return string
	 */
	public function description()
	{
		return  'Import Archive';
	}


	/**
	 * Handle the command
	 */
	public function handle()
    {
		ini_set('memory_limit', '1G');
		//$article = $this->article(575296);
		//$this->galleries();
		$this->articles();
		//$this->videos();
		//var_dump($article);



		/**
		 * b_iblock_section IBLOCK_ID: 76 zijn galleries
		 * b_iblock_element met IBLOCK_SECTION_ID zijn de foto's
		 */


		/*
		elements met IBLOCK_ID



		33: metro stations
		34: plekken
		35,  36: events
		39, 40, 41: things to do

		37: artikelen moscow guide
		38: feitjes

		55, 59, 64, 69, 77: ads / career / real estate?

		60: pages
		62: highlights


		141: blog posts

		-----------------
		42, 49, 104: nieuwsartikelen
		53: art and ideas artikelen
		130 videos


		97: people profiles artikelen
		120 lost in translation articles
		167: chinese border articles / parts


		14, 47, 86: fotos
		76 gallery-fotos
		90, 91: issue covers?


		43: issues

		48, 51: authors
		52: source
		*/
    }



	// archive_article
	// archive_author
	// archive_article_author
	// archive_image
	// archive_image_set



	public function articles()
	{
		$existing = $this->dest
		->select('id')
		->from('archive_article')
		->result(null, 'id');


		$articleIds = $this->db->select('ID')
		->from('b_iblock_element')
		->where('IBLOCK_ID', 'IN', [42, 49, 104, 53])
		->result();
			var_dump(count($articleIds));
		exit;
		$current = 1000;
		$chunck = 1000;
		$total = 400000;



		while($current < $total) {

			$articles = $this->db->select()
			->from('b_iblock_element')
			->where('IBLOCK_ID', 'IN', [42, 49, 104, 53])
			->where('ID', 'NOT IN', $existing)
			->limit($chunck)
			->offset($current)
			->iterator();

			$current += $chunck;

			foreach($articles as $article) {

				$data = [
					'id' => $article['ID'],
					'slug' => $this->slug('archive_article', $article['NAME'], $article['ID']),
					'status' => 'live',
					'created' => $article['DATE_CREATE'],
					'timestamp' => $article['TIMESTAMP_X'],
					'time_publication' => $article['ACTIVE_FROM'],
					'type' => 'default',
					'section' => $this->section($article['ID']),
					'caption' => $this->caption($article['ID']),
					'credits' => $this->credits($article['ID']),
					'title' => $article['NAME'],
					'excerpt' => strip_tags($article['PREVIEW_TEXT']),
					'body' => json_encode([
						[
							'type' => 'html',
							'body' => $article['DETAIL_TEXT']
						]
					],JSON_PRETTY_PRINT),
					'zone' => 'main'
				];

				if($imageId = $this->image($article['DETAIL_PICTURE'])) {
					$data['archive_image_id'] = $imageId;
				}

				foreach($this->authors($article['ID']) as $authorId) {
					$this->dest->insert('archive_article_author')
					->values([
						'archive_article_id' => $article['ID'],
						'archive_author_id' => $authorId
					])
					->execute();
				}

				// create article
				$this->dest
				->insert('archive_article')
				->values($data)
				->execute();

				// var_dump($data);
			}
		}
	}


	public function videos()
	{
		$existing = $this->dest
		->select('id')
		->from('archive_article')
		->result(null, 'id');

		$articles = $this->db->select()
		->from('b_iblock_element')
		->where('IBLOCK_ID', '130')
		->where('ID', 'NOT IN', $existing)
		->result();

		foreach($articles as $article) {
			$data = [
				'id' => $article['ID'],
				'slug' => $this->slug('archive_article', $article['NAME'], $article['ID']),
				'status' => 'live',
				'created' => $article['DATE_CREATE'],
				'timestamp' => $article['TIMESTAMP_X'],
				'time_publication' => $article['DATE_CREATE'],
				'title' => $article['NAME'],
				'excerpt' => '',
				'type' => 'video',
				'section' => $this->section($article['ID']),
				'intro' => '',
				'body' => json_encode([
					[
						'type' => 'html',
						'body' => $article['PREVIEW_TEXT']
					]
				], JSON_PRETTY_PRINT),
				'zone' => 'main'
			];

			if($video = preg_match('#src\=\"([^\"]+)\"#', $article['DETAIL_TEXT'], $matches) ){
				$data['video'] = $matches[1];
			}

			if($imageId = $this->image($article['DETAIL_PICTURE'])) {
				$data['archive_image_id'] = $imageId;
			}

			// create article
			$this->dest
			->insert('archive_article')
			->values($data)
			->execute();
		}
	}



	public function galleries()
	{
		$existing = $this->dest
		->select('id')
		->from('archive_article')
		->result(null, 'id');

		$articles = $this->db->select()
		->from('b_iblock_section')
		->where('IBLOCK_ID', '76')
		->where('ID', 'NOT IN', $existing)
		->result();

		foreach($articles as $article) {
			$data = [
				'id' => $article['ID'],
				'slug' => $this->slug('archive_article', $article['NAME'], $article['ID']),
				'status' => 'live',
				'created' => $article['DATE_CREATE'],
				'timestamp' => $article['TIMESTAMP_X'],
				'time_publication' => $article['DATE_CREATE'],
				'title' => $article['NAME'],
				'type' => 'gallery',
				'section' => '',
				'intro' => $article['DESCRIPTION'],
				'body' => [],
				'archive_image_set_id' => $this->dest
					->insert('archive_image_set')
					->values(['set_id' => -1, 'archive_image_id' => -1])
					->result(),
				'zone' => 'main'
			];

			// create article
			$this->dest
			->insert('archive_article')
			->values($data)
			->execute();

			// add images
			$images = $this->db
			->select()
			->from('b_iblock_element')
			->where('IBLOCK_SECTION_ID', $article['ID'])
			->result();

			foreach($images as $image) {
				if($imageId = $this->image($image['DETAIL_PICTURE'] ?:  $image['PREVIEW_PICTURE'])) {
					$this->dest
					->insert('archive_image_set')
					->values([
						'set_id' => $data['archive_image_set_id'],
						'archive_image_id' => $imageId,
						'caption' => $image['DETAIL_TEXT'],
						'credits' => $this->credits($image['ID'])
					])
					->execute();
				}
			}
		}
	}



	protected function slug($table, $title, $id)
	{
		$slug = $this->slug->clean($title);

		$existing = $this->dest
		->select('id')
		->from($table)
		->where('slug', $slug)
		->result();

		if(count($existing) > 0) {
			return $slug . '-' . $id;
		} else {
			return $slug;
		}
	}


	public function section($elementId)
	{
		$sections = [
			854 => 'opinion',
			1016 => 'opinion',
			1497 => 'opinion',
			4852 => 'opinion',
			4898 => 'opinion',

			852 => 'business',
			1009 => 'business',
			1168 => 'business',
			1496 => 'business',
			4851 => 'business',
			4897 => 'business',
			5216 => 'business',
			6291 => 'business',

			452 => 'news',
			452 => 'news',
			850 => 'news',
			1007 => 'news',
			1042 => 'news',
			851 => 'news',
			1123 => 'news',
			1133 => 'news',
			1298 => 'news',
			1010 => 'news',
			1042 => 'news',
			1298 => 'news',
			1303 => 'news',
			1422 => 'news',
			1426 => 'news',
			1428 => 'news',
			1495 => 'news',
			3131 => 'news',
			4113 => 'news',
			4745 => 'news',
			4850 => 'news',
			5214 => 'news',
			5220 => 'news',


			435 => 'city',
			1013 => 'city',
			1054 => 'city',
			1159 => 'city',
			1499 => 'city',
			3845 => 'city',
			4853 => 'city',
		];

		$section = '';

		foreach(
			$this->db->select('IBLOCK_SECTION_ID')
			->from('b_iblock_section_element')
			->where('IBLOCK_ELEMENT_ID', '=', $elementId)
			->result() as $sec
		) {
			if(isset($sections[$sec['IBLOCK_SECTION_ID']])) {
				$section = $sections[$sec['IBLOCK_SECTION_ID']];
			}
		}
		return $section;
	}



	protected $sTables = [114, 115, 161, 174, 175, 178, 182, 206, 208, 210, 42, 49, 53 ,59, 64, 77, 99];




	protected function caption($elementId)
	{
		static $captionProperties;
		if(is_null($captionProperties)) {
			$captionProperties = $this->db->select('ID', 'IBLOCK_ID')
			->from('b_iblock_property')
			->where('CODE', 'in', ['F_CAPTION'])
			->result('ID', 'IBLOCK_ID');
		}

		foreach($captionProperties as $propertyId => $table){
			if(in_array($table, $this->sTables)) {
				$properties = $this->db->select()
				->from('b_iblock_element_prop_s' . $table)
				->where('IBLOCK_ELEMENT_ID', $elementId)
				->result();
				$column = 'PROPERTY_' . $propertyId;
				foreach($properties as $property){
					if(isset($property[$column]) && $property[$column]) {
						return $property[$column];
					}
				}
			}
		}
		return '';
	}



	protected function credits($elementId)
	{
		static $creditsProperties;
		if(is_null($creditsProperties)) {
			$creditsProperties = $this->db->select('ID', 'IBLOCK_ID')
			->from('b_iblock_property')
			->where('CODE', 'in', ['F_SOURCE', 'F_AUTHOR'])
			->result('ID', 'IBLOCK_ID');
		}


		// try from s tables
		foreach($creditsProperties as $propertyId => $table){
			if(in_array($table, $this->sTables)) {
				$properties = $this->db->select()
				->from('b_iblock_element_prop_s' . $table)
				->where('IBLOCK_ELEMENT_ID', $elementId)
				->result();

				$column = 'PROPERTY_' . $propertyId;
				foreach($properties as $property){
					if(isset($property[$column]) && $property[$column]) {
						return $property[$column];
					}
				}
			}
		}

		// try from property table
		$properties = $this->db->select('VALUE')
		->from('b_iblock_element_property')
		->where('IBLOCK_ELEMENT_ID', $elementId)
		->where('IBLOCK_PROPERTY_ID', 'in', array_keys($creditsProperties))
		->result(null, 'VALUE');

		if(count($properties) > 0) {
			return implode(' / ', $properties);
		}

		return '';
	}



	protected function authors($elementId)
	{
		static $authorProperties;
		if(is_null($authorProperties)) {
			$authorProperties = $this->db->select('ID', 'IBLOCK_ID')
			->from('b_iblock_property')
			->where('CODE', 'in', ['AUTHOR', 'DATASOURCE'])
			->result('ID', 'IBLOCK_ID');
		}

		foreach($authorProperties as $propertyId => $table){
			if(in_array($table, $this->sTables)) {
				$properties = $this->db->select()
				->from('b_iblock_element_prop_s' . $table)
				->where('IBLOCK_ELEMENT_ID', $elementId)
				->result();

				$column = 'PROPERTY_' . $propertyId;

				foreach($properties as $property){
					if(isset($property[$column]) && $property[$column]) {
						$authorIds = [];

						$authorValue = @unserialize($property[$column]);
						if(is_array($authorValue)) {
							if(isset($authorValue['VALUE']) && $authorValue['VALUE']) {
								$authorIds = $authorValue['VALUE'];
								if(! is_array($authorIds)) {
									$authorIds = [$authorIds];
								}
							}
						} else {
							$authorIds = [$property[$column]];
						}


						$finalIds = [];
						foreach($authorIds as $authorId) {
							$existing = $this->dest
							->select('id')
							->from('archive_author')
							->where('id', $authorId)
							->result();


							if(count($existing) > 0) {
								// author exists, just use the id
								$finalIds[] = $authorId;
							} else {
								// we need to create the author, get info
								$author = $this->db->select()
								->from('b_iblock_element')
								->where('id', $authorId)
								->result();

								if(count($author)) {
									$author = $author[0];

									// create author
									$this->dest
									->insert('archive_author')
									->values([
										'id' => $authorId,
										'created' => date('Y-m-d H:i:s'),
										'title' => $author['NAME'],
										'status' => 'live',
										'zone' => 'main',
										'user_id' => 1
									])
									->execute();

									$finalIds[] = $authorId;
								}
							}
						}


						// if we've got something, use that
						if(count($finalIds) > 0) {
							return $finalIds;
						}
					}
				}
			}
		}
		// nothing
		return [];
	}





	public function article($id)
	{
		$article = $this->db->select()
		->from('b_iblock_element')
		->where('ID', '=', $id)
		->result();


		if(count($article) > 0) {
			$article = $article[0];
			//return $article;
			$data = [
				'created' => $article['DATE_CREATE'],
				'timestamp' => $article['TIMESTAMP_X'],
				'time_publication' => $article['ACTIVE_FROM'],
				'title' => $article['NAME'],
				'excerpt' => $article['PREVIEW_TEXT'],
				'body' => [
					[
						'type' => 'html',
						'body' => $article['DETAIL_TEXT']
					]
				],
			];

			if($article['DETAIL_PICTURE']) {
				$data['image'] = $this->image($article['DETAIL_PICTURE']);
			}


			$data['section'] = $this->section($id);
			$data['caption'] = $this->caption($id);
			$data['credits'] = $this->credits($id);
			$data['authors'] = $this->author($id);

		}
	}





	protected function image($fileId)
	{
		if(! $fileId) {
			return 0;
		}

		$existing = $this->dest
		->select('id')
		->from('archive_image')
		->where('id', $fileId)
		->result();

		if(count($existing) > 0) {
			return $fileId;
		}

		$image = $this->db->select()
		->from('b_file')
		->where('ID', '=', $fileId)
		->result();

		if(count($image) > 0) {
			$image = $image[0];
			$dir = trim($image['SUBDIR'], '/');
			$dir = $dir == '' ? '' : $dir . '/' ;

			$file = 'upload/' . $dir . $image['FILE_NAME'];


			$this->dest
			->insert('archive_image')
			->values([
				'id' => $fileId,
				'created' => date('Y-m-d H:i:s'),
				'title' => $file,
				'file' => $file,
				'width' => $image['WIDTH'],
				'height' => $image['HEIGHT'],
				'status' => 'import',
				'zone' => 'main',
				'user_id' => 1
			])
			->execute();

			return $fileId;
		} else {
			return 0;
		}
	}
}