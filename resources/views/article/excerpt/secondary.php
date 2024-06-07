<?php
$bem = fetch::bem('article-excerpt-secondary', $context ?? null, $modifier ?? null);
$modifier = fetch::section($item);
$author = [];
$archive = $archive ?? false;
$url = $archive ? fetch::route('archive_article', $item->data()) : fetch::route('article', $item->data());
?>

<div
	class="article-excerpt-secondary"
	data-url="<?php view::text($url); ?>"
	data-title="<?php view::text($item->title); ?>"
>
	<a href="<?php view::route('article', $item->data())?>" class="article-excerpt-secondary__link" title="<?php view::attr($item->title); ?>">
			
		<h3 class="article-excerpt-secondary__title">
			<?php if ($item->title_live): ?>
				<!-- LIVE Label -->
				<span class="wrap live-label-wrap">
					<span class="pulsating-icon"></span>
					<span>LIVE |</span>
				</span>
			<?php endif; ?>
			<?php view::text($item->title); ?>
		</h3>		
	
		<div class="article-excerpt-secondary__teaser">
			<?php view::file('common/label', ['item'=>$item, 'keyword'=> false,  'context' => $bem->block()]) ?> 
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
		</div>
		<?php if ($item->duration > 0): ?>
			<div class="readtime">
			<?php view::text($item->duration); ?>&nbsp;<?php view::lang('Min read'); ?>		
			</div>
		<?php endif; ?>
	</a>
</div>