<?php
$bem = fetch::bem('article-excerpt-lead', $context ?? null, $modifier ?? null);
$modifier = fetch::section($item);
$author = [];
$archive = $archive ?? false;
$url = $archive ? fetch::route('archive_article', $item->data()) : fetch::route('article', $item->data());
?>

<div
	class="<?php view::attr($bem()) ?> "
	data-url="<?php view::text($url); ?>"
	data-title="<?php view::text($item->title); ?>"
>
	<a href="<?php view::attr($url)?>" class="<?php view::attr($bem('link')) ?>" title="<?php view::attr($item->title); ?>">
		<div class=" <?php view::attr($bem('image-wrapper')) ?>">
			<figure>
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
		</div>
		<div class="<?php view::attr($bem('content')) ?>">
			<?php view::file('common/label', [
				'item' => $item,
				'label' => $label ?? true,
				'nolabel' => $nolabel ?? [],
				'context' => $bem->block()
			]) ?>
			<?php if($item->opinion && is_array($item->authors) && count($item->authors) > 0 ) : ?>
			<?php
			$authorTags = [];
			foreach ($item->authors as $author) {
					$authorTags[] = '<span class="article-excerpt-lead__author">' . fetch::text($author->title) . '</span>';
			}
			$authorHtml = '';
			$separator = '';
			while($authorTag = array_shift($authorTags)) {
				$authorHtml .= $separator . $authorTag;
				if(count($authorTags) > 1) {
					$separator = '<span style="padding-left:0; padding-right:0;" class="article-excerpt-lead__author">and</span>';
				} else {
					$separator = '<span style="padding-left:0; padding-right:0;" class="article-excerpt-lead__author">and</span>';
				}
			}
			view::raw($authorHtml);
			?>
			<?php endif; ?>
			<h3 class="<?php view::attr($bem('headline')) ?>">
				<span class="wrap wrap1">
					<span class="wrap wrap2">

						<?php if ($item->opinion): ?>
						<?php foreach ($item->authors as $author): ?>
							<?php if ($author->image): ?>
								<span class="<?php view::attr($bem('author-image-wrapper')) ?>"><img class="<?php view::attr($bem('author-image')) ?>" src="<?php view::src($author->image, '320') ?>" /></span>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php endif; ?>

						<?php if ($item->title_live): ?>
							<!-- LIVE Label -->
							<span class="wrap live-label-wrap">
								<span class="pulsating-icon"></span>
								<span>LIVE |</span>
							</span>
						<?php endif; ?>

						<span><?php view::text($item->title); ?></span>
					</span>
				</span>
			</h3>
			<div class="<?php view::attr($bem('teaser')) ?>">

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

			<?php if($item->opinion && $item->author) : ?>
				<div class="<?php view::attr($bem('author')) ?>">
					<?php view::text($item->author->title); ?>
				</div>
			<?php endif; ?>
		</div>

	</a>
</div>