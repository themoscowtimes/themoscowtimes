<?php
if (isset($block['steps'])) {
	$steps = range(0, $block['steps']);
}
// Load CSS & JS for animations
view::asset('js', 'https://unpkg.com/scrollama@3.2.0/build/scrollama.min.js');
view::asset('css', fetch::url('static') . 'css/scrollama.css');

?>

<section id="scrolly" y-use="Scrollama">
	<figure class="figure" y-name="figure">
		<?php view::raw($block['scrollama']); ?>
	</figure>

	<article class="article_1" y-name="article">
		<?php if(isset($steps)): ?>
		<?php foreach($steps as $step): ?>
		<?php if($step !== 0): ?>
		<span class="step" data-step="<?php view::raw($step); ?>" y-name="step"></span>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>

	</article>
</section>