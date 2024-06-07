<?php
$bem = fetch::bem('article-excerpt-lead', $context ?? null, $modifier ?? null);
$modifier = fetch::section($item);
$url =  fetch::route('advertorial', ['campaign' => $campaign->slug, 'slug' => $item->slug]);
?>


<div class="<?php view::attr($bem()) ?> ">
	<a href="<?php view::attr($url) ?>" class="<?php view::attr($bem('link')) ?>" title="<?php view::attr($item->title); ?>">
		<div class=" <?php view::attr($bem('image-wrapper')) ?>">
			<figure>
				<?php view::file('article/excerpt/image', ['item' => $item, 'preset' => 'article_1360']); ?>

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
		<div class="<?php view::attr($bem('content')) ?>">
			<h3 class="<?php view::attr($bem('headline')) ?>">
				<span class="wrap wrap1">
					<span class="wrap wrap2">
						<?php view::text($item->title); ?>
					</span>
				</span>
			</h3>
			<div class="<?php view::attr($bem('teaser')) ?> <?php view::attr($bem('teaser--advertorial')) ?>">
				<span class="wrap wrap1">
					<span class="wrap wrap2">
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
					</span>
				</span>
			</div>
		</div>
	</a>
</div>