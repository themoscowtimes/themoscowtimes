<?php foreach ($meta as $name => $value): ?>
	<meta name="<?php view::attr($name) ?>" content="<?php view::attr($value) ?>">
<?php endforeach; ?>