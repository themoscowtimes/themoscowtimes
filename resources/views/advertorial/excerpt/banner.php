<?php
$modifier = $modifier ?? (fetch::section($item)) ;
$bem = fetch::bem('article-excerpt-default', $context ?? null, $modifier ?? null);
$url =  fetch::route('advertorial', ['campaign' => $item->campaign->slug, 'slug' => $item->slug]);
$image = $image ?? 'article_640';
?>

<div class="article-excerpt-default article-excerpt-default--primary">
	<a href="<?php view::attr($url)?>" class="<?php view::attr($bem('link')) ?>" title="<?php view::attr($item->title); ?>">
		<?php if($image): ?>
			<div class=" <?php view::attr($bem('image-wrapper')) ?>">
				<figure>
					<?php view::file('article/excerpt/image', ['item' => $item, 'preset' => $image]); ?>

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
				</figure>
			</div>
		<?php endif; ?>

		<div class="<?php view::attr($bem('content')) ?>">
			<h3 class="<?php view::attr($bem('headline')) ?>">
				<?php view::text($item->title); ?>
			</h3>
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
		</div>
		
		<?php if ($item->campaign->logo): ?>
			<figure class="<?php view::attr($bem('campaign')) ?>" >
				<img class="<?php view::attr($bem('campaign__logo')) ?>" src="<?php view::src($item->campaign->logo, '320') ?>" title="<?php view::text($item->campaign->title ? $item->campaign->title :'') ?>" />
			</figure>
		<?php endif; ?>
	</a>
</div>