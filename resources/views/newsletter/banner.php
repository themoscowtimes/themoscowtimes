<?php
$types = [
	'default' => [
		'id' => 'newsletter',
		'title' => 'TMT Weekly Newsletter',
		'description' => 'Our editors take you through the biggest Russia stories as featured in The Moscow Times.',
		'policy' => fetch::url('base') . 'page/privacy-policy',
		'privacy' => '',
		'modifier' => 'newsletterbanner--landscape mb-3',
	],
	'bell' => [
		'id' => 'newsletterBell',
		'title' => 'Russian Money Newsletter',
		'description' => 'An inside look at economics, politics and business in wartime Russia by The Moscow Times and The Bell.',
		'policy' => fetch::url('base') . 'page/privacy-policy',
		'privacy' => ' & to share personal details with The Bell',
		'modifier' => 'mb-3',
	]
];
$data = $types[$type ?? 'default'];
?>

<div
	class="newsletterbanner <?php view::attr($data['modifier']) ?>"
	y-use="newsletter.Banner"
	data-newsletter="<?php view::attr($data['id']) ?>"
	data-url="<?php view::route('newsletter'); ?>"
>
	<h4 class="newsletterbanner__title"><?php view::text($data['title']) ?></h4>
	<div class="newsletterbanner__teaser">
		<?php view::text($data['description']) ?>
		<a href="<?php view::route('newsletterpreview', ['type' => $type ?? 'default']); ?>" target="_blank" class="newsletterbanner__teaser__link">Preview</a>
	</div>
	<div>
		<div class="newsletterbanner__inputs">
			<input type="email" placeholder="<?php view::lang('Your email'); ?>" y-name="email" />
			<input type="text" placeholder="<?php view::lang('Your name'); ?>" y-name="name" />
			<button class="newsletterbanner__button button button--color-3" y-name="submit"><?php view::lang('Subscribe'); ?></button>
		</div>
		<span class="newsletterbanner__disclaimer">
			<em>Subscribers agree to the <a href="<?php view::attr($data['policy']) ?>">Privacy Policy</a> <?php view::text($data['privacy']) ?></em>
		</span>
		<div class="newsletterbanner__error" y-name="error" style="display:none"></div>
		<div class="newsletterbanner__message" y-name="done" style="display:none">Thanks for signing up!</div>
	</div>
</div>