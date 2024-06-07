<?php foreach ($properties as $property => $value): ?>
	<meta property="<?php view::attr($property) ?>" content="<?php view::attr($value) ?>">
<?php endforeach; ?>

