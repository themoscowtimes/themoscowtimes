<?php

namespace Search;

use Sulfur\Data;

use Article\Model as ArticleModel;
use Author\Model as AuthorModel;
use Archive\Article\Model as ArchiveModel;
use Archive\Author\Model as ArchiveAuthorModel;
use Sphinx\Client as Sphinx;
use Url;

class Model
{
	/**
	 * The sphinx client
	 * @var Sphinx\Client
	 */
	protected $sphinx;

	public function __construct(
		Data $data,
		Sphinx $sphinx,
		ArticleModel $article,
		AuthorModel $author,
		ArchiveModel $archive,
		ArchiveAuthorModel $archiveAuthor,
		Url $url
	)
	{
		$this->data = $data;
		$this->sphinx = $sphinx;
		$this->article = $article;
		$this->author = $author;
		$this->archive = $archive;
		$this->archiveAuthor = $archiveAuthor;
		$this->url = $url;
	}



	public function searchAuthors($query)
	{
		$words = $this->words($this->prepare($query)['words']) ;
		$prepared = [];

		$this->sphinx->resetFilters();
		$this->sphinx->SetFieldWeights([]);
		$this->sphinx->setLimits(0, 100, 1000);
		$query = '(' . $words . ')';
		$this->sphinx->setFieldWeights([
			'title' => 1000,
		]);

		$this->sphinx->SetSortMode(SPH_SORT_EXPR, "@weight");

		$result = $this->sphinx->query($query, 'themoscowtimes_author')['matches'] ?? [];
		foreach($result as $row) {
			$prepared[] = [
				'title' => ($row['attrs']['title']) ?? '',
				'url' => $this->url->route('author', $row['attrs']),
				'type' => 'current'
			];
		}

		$result = $this->sphinx->query($query, 'themoscowtimes_archiveauthor')['matches'] ?? [];

		$found = [];
		foreach($result as $id => $row) {
			$normalized = $this->normalizeAuthor($row['attrs']['title']);
			if(! in_array($normalized, $found)) {
				$found[] = $normalized;
				$prepared[] = [
					'title' => ($row['attrs']['title']) ?? '',
					'normalized' => $normalized,
					'url' => $this->url->route('archive_author', [
						'slug' => $normalized,
					]),
					'type' => 'archive'
				];
			}
		}

		return $prepared;
	}


	protected function normalizeAuthor($name) {
		$normal = strtolower(str_replace(
			['?', '…', 'À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','ß','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ',"'",'*'],
			['', '','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','ss','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','',''],
			$name
		));

		return trim(preg_replace('#[^a-zA-Z0-9]{1,}#', '-', $normal), '-');
	}


	public function searchArticles($query, $filters = [], $order = null)
	{
		$prepared = $this->prepare($query);
		$query = $this->words($prepared['words']);
		$section = isset($filters['section']) &&  $filters['section'] ? $filters['section'] : false;
		$from = isset($filters['from']) &&  $filters['from'] ? strtotime($filters['from'] . ' 00:00:00') : 0;
		$to = isset($filters['to']) &&  $filters['to'] ? strtotime($filters['to'] . ' 23:59:59') : 9999999999999999;
		$types = ['video', 'gallery', 'podcast', 'live'];


		//--------
		// Articles
		//--------
		$this->sphinx->resetFilters();
		$this->sphinx->SetFieldWeights([]);
		$this->sphinx->setLimits(0, 100, 1000);
		//$this->sphinx->setMatchMode(SPH_MATCH_EXTENDED2);
		$articleQuery = '(' . $this->words($prepared['words']) . ') && @status "live" ';
		if($section) {
			if(in_array($section, $types) ) {
				 $articleQuery .= ' && @type "' . $section . '" ';
			} else {
				$this->sphinx->setFilter($filters['section'], [1]);
			}
		}
		$this->sphinx->setFilterRange('time_publication', $from, $to);
		if($order == 'date') {
			$this->sphinx->SetSortMode(SPH_SORT_ATTR_DESC , 'time_publication');
		} elseif($order == 'date_reverse') {
			$this->sphinx->SetSortMode(SPH_SORT_ATTR_ASC , 'time_publication');
		} else {
			$this->sphinx->setFieldWeights([
				'title' => 1000,
				'subtitle' => 100,
				'intro' => 100,
				'body' => 1,
			]);
			$this->sphinx->SetSortMode(SPH_SORT_EXPR, "@weight");
		}
		$articleResult = $this->sphinx->query($articleQuery, 'themoscowtimes_article')['matches'] ?? [];

		if(count($articleResult) > 0) {
			foreach($this->article->set(array_keys($articleResult)) as $article)  {
				if(isset($articleResult[$article->id])) {
					$articleResult[$article->id]['article'] = $article;
					$articleResult[$article->id]['type'] = 'article';
				}
			}
		}



		//-------------
		// Archive
		//-------------
		$this->sphinx->resetFilters();
		$this->sphinx->SetFieldWeights([]);
		$this->sphinx->setLimits(0, 200, 1000);
		$archiveQuery = '(' . $this->words($prepared['words']) . ') && @status "live" ';
		if($section) {
			if(in_array($section, $types) ) {
				 $archiveQuery .= ' && @type "' . $section . '" ';
			} else {
				 $archiveQuery .= ' && @section "' . $section . '" ';
			}
		}
		$this->sphinx->setFilterRange('time_publication', $from , $to);
		if($order == 'date') {
			$this->sphinx->SetSortMode(SPH_SORT_ATTR_DESC , 'time_publication');
		} elseif($order == 'date_reverse') {
			$this->sphinx->SetSortMode(SPH_SORT_ATTR_ASC , 'time_publication');
		} else {
			$this->sphinx->setFieldWeights([
				'title' => 1000,
				'subtitle' => 100,
				'intro' => 100,
				'body' => 1,
			]);
			$this->sphinx->SetSortMode(SPH_SORT_EXPR, "@weight");
		}
		$archiveResult = $this->sphinx->query($archiveQuery, 'themoscowtimes_archive')['matches'] ?? [];
		if(count($archiveResult) > 0) {
			foreach($this->archive->set(array_keys($archiveResult)) as $article)  {
				if(isset($archiveResult[$article->id])) {
					$archiveResult[$article->id]['article'] = $article;
					$archiveResult[$article->id]['type'] = 'archive';
				}
			}
		}


		//--------------
		// Merge and sort results
		//-------------
		$results = array_merge($articleResult, $archiveResult);
		usort($results, function($a, $b) use ($order){
			if($order == 'date') {
				return $a['attrs']['time_publication'] < $b['attrs']['time_publication'];
			} elseif($order == 'date_reverse') {
				return $a['attrs']['time_publication'] > $b['attrs']['time_publication'];
			} else {
				return $a['weight'] < $b['weight'];
			}
		});


		//--------------
		// Prepare merged
		//-------------
		$prepared = [];
		foreach($results as $result) {
			if(isset($result['article'])) {
				$article = $result['article'];
				if($article->image) {
					$image = $this->url->route($result['type'] == 'archive'  ? 'image_archive' : 'image', [
						'file' => $article->image->file,
						'path' => trim($article->image->path, '/\\'),
						'preset' => 'article_640',
					]);
				} else {
					$image = $this->url->route('img', ['file' => 'article_default.jpg']);
				}


				$summary = '';
				if( trim($article->excerpt)) {
					$summary =  $article->excerpt;
				} elseif(trim($article->intro)) {
					$summary =  $article->intro;
				} elseif(is_array($article->body)) {
					foreach($article->body as $block) {
						if($block['type'] == 'html' && isset($block['body']) &&  $block['body']) {
							$summary = $block['body'];
							break;
						}
					}
				}
				$summary = mb_substr(html_entity_decode(strip_tags($summary)), 0 ,250) . '...';



				$section = 'news';

				$prepared[] = [
					'id' => $article->id,
					'title' => $article->title,
					'type' => $result['type'],
					'weight' => $result['weight'],
					'date' => date('F j, Y',strtotime($article->time_publication)),
					'summary' => $summary,
					'url' => $this->url->route($result['type'] == 'archive' ? 'archive_article' : 'article', $article->data()),
					'image' => $image,
				];
			}
		}
		return $prepared;
	}



	public function articles($query)
	{

		$prepared = $this->prepare($query);

		$query = $this->words($prepared['words']);

		/*
		$this->sphinx->SetFieldWeights(array(
			'title'=>1000,
			'subtitle'=>100,
			'intro'=>100,
			'body'=>1,
		));
		$this->sphinx->SetSortMode(SPH_SORT_EXPR, "@weight");
*/

		$ids = $this->ids($this->result($query, 'themoscowtimes_article'));
		$articles = $this->article->set($ids);
		if(! is_array($articles)) {
			$articles = $articles->flat();
		}


		usort($articles, function($a, $b) {
			return strtotime($a->time_publication) < strtotime($b->time_publication);
		});
		return array_slice($articles, 0, 100);
	}



	public function archive($query)
	{
		$prepared = $this->prepare($query);
		$query = $this->words($prepared['words']);
		$ids = $this->ids($this->result($query, 'themoscowtimes_archive'));

		$articles = $this->archive->set($ids);
		if(! is_array($articles)) {
			$articles = $articles->flat();
		}
		//usort($articles, function($a, $b) {
		//	return strtotime($a->time_publication) < strtotime($b->time_publication);
		//});
		return array_slice($articles, 0, 100);
	}



	public function authors($query)
	{
		$prepared = $this->prepare($query);
		$query = $this->words($prepared['words']);
		$ids = $this->ids($this->result($query, 'themoscowtimes_author'));
		$authors = $this->author->set($ids);
		if(! is_array($authors)) {
			$authors = $authors->flat();
		}

		return $authors;
	}



	public function archiveAuthors($query)
	{
		$prepared = $this->prepare($query);

		$query = $this->data->database()
		->select('id')
		->from('archive_author');

		foreach($prepared['words'] as $word) {
			$query->where('title', 'LIKE', '%' . $word . '%');
		}
		$ids = $query->result(null, 'id');
		$authors = $this->archiveAuthor->set($ids);
		if(! is_array($authors)) {
			$authors = $authors->flat();
		}
		return $authors;
	}


	protected function result($query, $index)
	{
		$this->sphinx->SetLimits(0, 200, 1000);
		return $this->sphinx->query($query, $index);
	}


	protected function ids($result)
	{
		if(is_array($result) && isset($result['matches'])) {
			return array_keys($result['matches']);
		}
		return [];
	}



	protected function prepare($query)
	{
		$normalized = str_replace(
			['?', '…', 'À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','ß','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ',"'",'*'],
			['', '','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','ss','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','',''],
			$query
		);


		$prepared = [
			'raw' => $query,
			'prepared' => $this->sphinx->EscapeString(utf8_decode($normalized)),
			'words' => [],
		];

		$words = explode('#', preg_replace('/[\s\-\,\.\\\]+/i','#',$normalized));
		foreach($words as $word){
			if($word != '' && strlen($word) > 1){
				$prepared['words'][] = $this->sphinx->EscapeString(utf8_decode($word));
			}
		}
		return $prepared;
	}



	protected function filter($filters)
	{
		$filter = '';
		foreach($filters as $name => $value) {
			if(is_array($value)) {
				$value = implode('"|"', $value);
			}
			$filter .= ' @' . $name . ' "' . $value . '"';
		}
		return $filter;
	}


	protected function words($words)
	{
		$prepared = [];
		foreach($words as $word) {
			if(strlen($word) > 3) {
				$prepared[] = $word . '*';
			} else {
				$prepared[] = $word;
			}
		}
		return implode(' ', $prepared);
	}

}