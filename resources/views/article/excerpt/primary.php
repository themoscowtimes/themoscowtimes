<?php
$bem = fetch::bem('article-excerpt-primary', $context ?? null, $modifier ?? null);
$modifier = fetch::section($item);
$author = [];
$archive = $archive ?? false;
$url = $archive ? fetch::route('archive_article', $item->data()) : fetch::route('article', $item->data());
?>

<div
	class="article-excerpt-primary"
	data-url="<?php view::text($url); ?>"
	data-title="<?php view::text($item->title); ?>"
>
	<a href="<?php view::route('article', $item->data())?>" class="article-excerpt-primary__link" title="<?php view::attr($item->title); ?>">
		<div class="article-excerpt-primary__header">
			<figure class="article-excerpt-primary__figure">
				<?php view::file('article/excerpt/image', ['item' => $item, 'preset' => 'article_1360', 'archive' => $archive]); ?>

				<?php if($item->type=='video'): ?>
					<div class="<?php view::attr($bem('type-icon')) ?>">
						<i class="fa fa-play"></i>
					</div>
				<?php endif; ?>
				<?php if($item->type=='gallery'): ?>
					<div class="<?php view::attr($bem('type-icon')) ?>">
						<i class="fa fa-camera"></i>
					</div>
				<?php endif; ?>
				<?php if($item->type=='podcast'): ?>
					<div class="<?php view::attr($bem('type-icon')) ?>" >
						<i class="fa  fa-microphone"></i>
					</div>
				<?php endif; ?>
			</figure>		
			
			<h3 class="article-excerpt-primary__title">
				<span class="article-excerpt-primary__title__wrap">
					<?php if ($item->title_live): ?>
						<!-- LIVE Label -->
						<span class="wrap live-label-wrap">
							<span class="pulsating-icon"></span>
							<span>LIVE |</span>
						</span>
					<?php endif; ?>
					<?php view::text($item->title); ?>
				</span>
			</h3>
		</div>
		
	
		<div class="<?php view::attr($bem('teaser')) ?>">

			<?php view::file('common/label', ['item'=>$item, 'context' => $bem->block()]) ?> 
			<?php if($item->opinion && is_array($item->authors) && count($item->authors) > 0 ) : ?>
			<?php
			$authorTags = [];
			foreach ($item->authors as $author) {
					$authorTags[] = '<span class="article-excerpt-primary__author">' . fetch::text($author->title) . '</span>';
			}
			$authorHtml = '';
			$separator = '';
			while($authorTag = array_shift($authorTags)) {
				$authorHtml .= $separator . $authorTag;
				if(count($authorTags) > 1) {
					$separator = '<span style="padding-left:0; padding-right:0;" class="article-excerpt-primary__author">and</span>';
				} else {
					$separator = '<span style="padding-left:0; padding-right:0;" class="article-excerpt-primary__author">and</span>';
				}
			}
			view::raw($authorHtml);
			?>
			<?php endif; ?>


			<?php
			if($item->excerpt) {
				view::raw(nl2br(fetch::truncate(strip_tags($item->excerpt), 150)));
			} elseif($item->intro) {
				view::raw(nl2br(fetch::truncate(strip_tags($item->intro), 150)));
			} elseif(is_array($item->body)) {
				foreach ($item->body as $block) {
					if($block['type'] === 'html') {
						view::raw((fetch::truncate(strip_tags($block['body']), 150)));
						break;
					}
				}
			}
			?>
			&nbsp;
			
			<?php if ($item->duration > 0): ?>
				<div class="readtime readtime--inline">
					<?php view::text($item->duration); ?>&nbsp;<?php view::lang('Min read'); ?>		
				</div>
			<?php endif; ?>
		</div>	
	</a>
</div>