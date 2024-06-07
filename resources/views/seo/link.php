<?php foreach ($link as $rel => $href): ?>
	<link rel="<?php view::attr($rel) ?>" href="<?php view::attr($href) ?>">
<?php endforeach; ?>