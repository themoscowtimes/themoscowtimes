<blockquote class="article__quote">
	<?php view::raw(nl2br(strip_tags($block['body']))) ?>
</blockquote>
<?php if(isset($block['by']) && $block['by']!=''): ?>
	<cite>&mdash; <?php view::raw(nl2br(strip_tags($block['by']))) ?></cite>
<?php endif; ?>
