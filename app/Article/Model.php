<?php

namespace Article;

use Sulfur\Data;

class Model
{
	public function __construct(Data $data)
	{
		$this->data = $data;
	}


	public function preview($post)
	{
		if(isset($post['values'])) {
			$values = json_decode(base64_decode($post['values']), true);

			if(! $values['time_publication']) {
				$values['time_publication'] = date('Y-m-d H:i:s');
			}

			$item = $this->data->hydrate('Article\Entity', $values);

			if($item->slug) {
				$item->id = $this->data->database()
				->select('id')
				->from('article')
				->where('slug', $item->slug)
				->result(null, 'id')[0] ?? null;
			}
			return $item;
		}
	}




	public function one($id)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->with('partners', function($finder){
			$finder->where('status', 'live');
		})
		->with('partners.image')
		->with('authors.image')
		->with('image')
		->with('images')
		->with('tags')
		->with('articles.image')
		->with('articles.author')
		->where('id', $id);

		$this->live($articles);

		return $articles->one();
	}

	public function next($time)
	{
		$query = $this->data->database()
		->select('id')
		->from('article')
		->where('time_publication', '<', date('Y-m-d H:i:s', strtotime($time)))
		->order('time_publication', 'DESC')
		->limit(1);

		$this->live($query);

		return $query->result(null, 'id')[0] ?? null;

	}

	public function slug($slug)
	{
		$article = $this->data->finder('Article\Entity');

		$parts = explode('-', $slug);
		if(preg_match('#^a[0-9]+$#', $parts[count($parts) -1])) {
			$article->where('id', substr(array_pop($parts), 1));
		} else {
			$article->where('slug', $slug);
		}
		$this->live($article);

		return $article->one();
	}



	public function id($id)
	{
		return $this->data->finder('Article\Entity')
		->where('id', $id)
		->one();
	}


	public function viewed($articleId)
	{
		$database = $this->data->database(\Article\Entity::database());
		$database
		->update('article')
		->set([
			'views' => $database->raw('views + 1')
		])
		->where('id', $articleId)
		->execute();
	}


	public function tag($tag, $amount = 10, $skip = 0)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('image')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->join('tag_article','inner')
		->on('tag_article.article_id', 'article.id')
		->onWhere('tag_article.tag_id', $tag)
		->limit($amount)
		->offset($skip);

		$this->live($articles);
		$this->newest($articles);

		return $articles->all();
	}


	public function section($section, $amount = 10, $skip = 0)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('image')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->where($section, 1)
		->limit($amount)
		->offset($skip);

		$this->live($articles);
		$this->newest($articles);

		return $articles->all();
	}


	public function type($type, $amount = 10, $skip = 0)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('image')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->with('authors.image')
		->where('type', $type)
		->limit($amount)
		->offset($skip);

		$this->live($articles);
		$this->newest($articles);

		return $articles->all();
	}



	/**
	 * Get the podcasts for the /podcasts overview page
	 * These are podcasts nested by author
	 */
	public function podcasts($amount = 5)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('image')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->with('authors.image')
		->where('type', 'podcast')
		->where('id', '>', 64000)
		->limit(250);

		$this->live($articles);
		$this->newest($articles);

		$authors = [];
		foreach($articles->all() as $article) {
			foreach($article->authors as $author) {

				if(isset($authors[$author->id])) {
					$author = $authors[$author->id];
				} else {
					$authors[$author->id] = $author;
				}

				$articles = is_array($author->articles) ? $author->articles : [];
				if(count($articles) < $amount) {
					$articles[] = $article;
				}
				$author->articles = $articles;
			}
		}
		return $authors;
	}



	public function author($authorId, $amount = 10, $skip = 0)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('image')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->join('article_author', 'INNER')
		->on('article_author.article_id', 'article.id')
		->onWhere('article_author.author_id', $authorId)
		->limit($amount)
		->offset($skip);

		$this->live($articles);
		$this->newest($articles);

		return $articles->all();
	}

	public function partner($partnerId, $amount = 10, $skip = 0)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('image')
		->with('partners', function($finder){
			$finder->where('status', 'live');
		})
		->join('article_partner', 'INNER')
		->on('article_partner.article_id', 'article.id')
		->onWhere('article_partner.partner_id', $partnerId)
		->limit($amount)
		->offset($skip);

		$this->live($articles);
		$this->newest($articles);

		return $articles->all();
	}


	public function recent($amount = 10)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->where('sponsored', 0)
		->where('type', 'in', ['default', 'gallery', 'video'])

		->limit($amount);

		$this->live($articles);
		$this->newest($articles);

		return $articles->all();
	}


	public function mostread($amount = 10)
	{
		$articles = $this->data->finder('Article\Entity')
		->with('authors', function($finder){
			$finder->where('status', 'live');
		})
		->where('id', '>', 64000)
		->where('sponsored', 0)
		->where('russian', 0)
		->where('time_publication', '>', date('Y-m-d', time() - (1 * 24 * 3600)))
		->order('views', 'DESC')
		->limit($amount);

		$this->live($articles);

		return $articles->all();
	}


	public function month($year, $month)
	{

		$fromDate = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01 00:00:00';
		$toDate = ($month == 12 ? $year + 1 : $year) . '-' . str_pad(($month == 12 ? 1 : $month + 1), 2, '0', STR_PAD_LEFT) . '-01 00:00:00';

		$articles = $this->data->finder('Article\Entity')
		->where('sponsored', 0)
		->where('time_publication', '>', $fromDate)
		->where('time_publication', '<', $toDate);

		$this->live($articles);
		$this->newest($articles);

		return $articles->all();
	}


	public function set(array $ids = [])
	{
		if(count($ids) > 0) {
			// escape ids for raw db input
			array_walk($ids, function($id){ return (int) $id; });

			// get finder
			$finder = $this->data->finder('Article\Entity');

			// get articles
			$articles = $finder
			->with('image')
			->with('authors', function($finder){
				$finder->where('status', 'live');
			})
			->where('id', 'in', $ids)
			->order($finder->raw('FIELD(id,' . implode(',', $ids) . ' )'), 'ASC');

			$this->live($articles);

			return $articles->all();
		} else {
			return [];
		}
	}


	public function related($article = null, $amount = 10)
	{
		if($article === null) {
			return $this->recent($amount);
		} else {
			// start with manually added articles
			$articles = $article->articles;
			// can be an array or a finder, depending on preview / not preview
			if(! is_array($articles)) {
				$articles = $articles
				->with('image')
				->with('authors', function($finder){
					$finder->where('status', 'live');
				})
				->all(true);
			}

			// when it is a gallery, video or podcast, show more of this type
			if($article->type == 'gallery' || $article->type == 'video' || $article->type == 'podcast') {
				// filter out the article itself
				foreach($this->type($article->type, $amount + 1) as $typeArticle) {

					if($typeArticle->id != $article->id) {
						$articles[] = $typeArticle;
					}
				}
				return array_slice($articles, 0, $amount);
			}


			// get the articles tags
			$tags = $article->tags;

			// get the primary aticle section
			$section = $article->meanwhile == 1 ? 'meanwhile' : (
				$article->city == 1 ? 'city' : (
					$article->opinion == 1 ? 'opinion' : 'news'
				)
			);

			if(count($tags) == 0) {
				// no tags, get articles of the same section filter out the article itself
				foreach($this->section($section, $amount + 1) as $sectionArticle) {
					if($sectionArticle->id != $article->id) {
						$articles[] = $sectionArticle;
					}
				}
				return array_slice($articles, 0, $amount);
			} else {
				//  tags: get articles with the same tags
				$tagIds = [];
				foreach($tags as $tag) {
					$tagIds[] = $tag->id;
				}

				// get all articles with one or more of these tags
				$taggedArticleIds = [];
				foreach(
					$this->data->finder('Article\Entity')
					->only('tag.id')
					->join('tag_article','inner')
					->on('tag_article.article_id', 'article.id')
					->onWhere('tag_article.tag_id', 'in' ,$tagIds)
					->all() as $taggedArticle
				) {
					$taggedArticleIds[] = $taggedArticle->id;
				}

				// count the number of times an article is included. more is better.
				// get the highest scoring
				$count = array_count_values($taggedArticleIds);

				arsort($count);

				$articleIds = array_slice(array_keys($count), 0, $amount + 1);

				// get full articles
				if(count($articleIds) > 0) {
					$tagArticles = $this->data->finder('Article\Entity')
					->with('image')
					->with('authors')
					->where('id', 'in', $articleIds);

					$this->live($tagArticles);
					$this->newest($tagArticles);

					$tagArticles = $tagArticles->all();

					foreach($tagArticles as $tagArticle) {
						if($tagArticle->id != $article->id) {
							$articles[] = $tagArticle;
						}
					}
				}


				if(count($articles) < $amount) {
					// not enough articles: add articles from the same section
					foreach($this->section($section, $amount - count($articles) + 1) as $sectionArticle) {
						if($sectionArticle->id != $article->id) {
							$articles[] = $sectionArticle;
						}
					}
				}
				return array_slice($articles, 0, $amount);
			}
		}
	}


	public function export($filters = [], $limit = 9999, $offset = 0)
	{
		$chunck = 200;
		for($current = 0; $current < $limit; $current += $chunck) {

			$articles = $this->data->finder('Article\Entity')
			->with('tags')
			->with('image')
			->with('images')
			->with('tags')
			->with('authors', function($finder){
				$finder->where('status', 'live');
			})
			->limit(min($limit - $current, $chunck))
			->offset($offset + $current);

			if(isset($filters['from'])) {
				$articles->where('timestamp', '>=', date('Y-m-d', $filters['from']));
			}

			if(isset($filters['type'])) {
				$articles->where('type', $filters['type']);
			} else {
				$articles->where('type', 'in', ['default', 'gallery', 'video', 'podcast']);
			}

			if(isset($filters['section'])) {
				$articles->where($filters['section'],1);
			}

			$this->live($articles);
			$this->newest($articles);

			foreach($articles->all() as $article) {
				yield $article;
			}
		}
  }


	public function live($finder)
	{
		$finder->where('status', 'live')
		->where('time_publication', '<', date('Y-m-d H:i:s'));
		return $finder;
	}



	public function newest($finder)
	{
		$finder
		->order('time_publication', 'desc');
		return $finder;
	}

}