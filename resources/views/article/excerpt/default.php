<?php
$lectures = fetch::section($item) == 'lecture_series';
$modifier = $lectures ? $modifier = '' : $modifier = (fetch::section($item));
$bem = fetch::bem('article-excerpt-default', $context ?? null, $modifier ?? null);
$archive = $archive ?? false;
$url = $archive ? fetch::route('archive_article', $item->data()) : fetch::route('article', $item->data());
$image = $image ?? 'article_640';
?>


<div
	class="<?php view::attr($bem()) ?>"
	data-url="<?php view::text($url); ?>"
	data-title="<?php view::text($item->title); ?>"
>

	<a href="<?php view::attr($url)?>" class="<?php view::attr($bem('link')) ?>" title="<?php view::attr($item->title); ?>">
		<?php if($image): ?>
			<div class=" <?php view::attr($bem('image-wrapper')) ?>">
				<figure>
					<?php view::file('article/excerpt/image', ['item' => $item, 'preset' => $image, 'archive' => $archive]); ?>

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
			</div>
		<?php endif; ?>

		<div class="<?php view::attr($bem('content')) ?>">
			<?php if(! isset($label) || $label): ?>
				<?php view::file('common/label', [
					'item' => $item,
					'label' => $label ?? true,
					'nolabel' => $nolabel ?? [],
					'context' => $bem->block()]
				) ?>
			<?php endif; ?>
			<?php if($item->opinion && is_array($item->authors) && count($item->authors) > 0 ) : ?>
				<?php if ($modifier == 'opinion' && $author->image): ?>
					<span class="<?php view::attr($bem('author-image-wrapper')) ?>"><img class="<?php view::attr($bem('author-image')) ?>" src="<?php view::src($author->image, '320') ?>" alt="<?php view::text($author->title); ?>" /></span>
				<?php endif; ?>
				<?php
				$authorTags = [];
				foreach ($item->authors as $author) {
						$authorTags[] = '<span class="article-excerpt-default__author">' . fetch::text($author->title) . '</span>';
				}
				$authorHtml = '';
				$separator = '';
				while($authorTag = array_shift($authorTags)) {
					$authorHtml .= $separator . $authorTag;
					if(count($authorTags) > 1) {
						$separator = '<span class="article-excerpt-default__author">&nbsp;and&nbsp;</span>';
					} else {
						$separator = '<span class="article-excerpt-default__author">&nbsp;and&nbsp;</span>';
					}
				}
				view::raw($authorHtml);
				?>
			<?php endif; ?>

			<h3 class="<?php view::attr($bem('headline')) ?>">
				<?php if ($item->title_live): ?>
					<!-- LIVE Label -->
					<span class="wrap live-label-wrap">
						<span class="pulsating-icon"></span>
						<span>LIVE |</span>
					</span>
				<?php endif; ?>
				<?php view::text($item->title); ?>
			</h3>

			<?php if(isset($date) && $date): ?>
				<span class="label">
					<?php view::date($item->time_publication); ?>
				</span>
			<?php endif; ?>

			<?php if(! isset($teaser) || $teaser): ?>
				<div class="<?php view::attr($bem('teaser')) ?>"><?php
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
				?></div>
			<?php endif; ?>
			<?php if ($item->duration > 0 && (! isset($duration) || $duration)): ?>
				<div class="readtime">
					<?php view::text($item->duration); ?>&nbsp;<?php view::lang('Min read'); ?>
				</div>
			<?php endif; ?>
		</div>
	</a>
</div>