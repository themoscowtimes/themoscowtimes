<?php view::asset('js', fetch::base().'vendor/tinymce/Tinymce.js'); ?>

<!-- Text area -->
<span
	y-use="manager.form.element.Tinymce"
	y-name="element element-<?php view::attr($element->key) ?> <?php view::attr($element->id); ?>"
	data-key="<?php view::attr($element->key) ?>"
	data-value="<?php view::attr($element->value) ?>"
	data-config="<?php view::attr(json_encode([
		'skin_url' =>  fetch::base() . 'vendor/tinymce/skins/lightgray/',
		'language_url' =>  fetch::base() . 'vendor/tinymce/langs/' . fetch::lang() . '.js',
		'branding'=> false,
		'theme' => 'modern',
		'height' => 400,
		'language' => fetch::lang(),
		'relative_urls' => false,
		'remove_script_host' => false,
		'convert_urls' => false,
		'autoresize_bottom_margin' => 20,
		'autoresize_min_height' => 200,
		'autoresize_max_height' => 500,

		'menubar' => false,
		'plugins' => 'lists wordcount code link unlink paste autoresize', // autosaves
		'toolbar' => 'formatselect | bold italic underline | code | bullist numlist | link unlink', // restoredraft - bug with close btn


		'autosave_ask_before_unload' => true,
		'autosave_interval' => '10s',

		'block_formats' => 'Paragraph=p;Header=h3',
		'image_caption' => true,
		'image_advtab' => true,
		'style_formats' => [],
		'formats' => [
			'underline' => ['inline' => 'span', 'classes' => 'underline','exact' => true],
		],
		'content_style' => ".underline { text-decoration: underline }",
		'valid_elements'=> 'span[id|class],strong/b,em/i,u/underline,a[href|target|id|class|title|rel],p[id|class],h2,h3,hr,br,ul,ol,li,sub,sup,div[id|class]',
		'paste_word_valid_elements'=> 'span[id|class],strong/b,em/i,u/underline,a[href|target],p[id|class],h2,h3,hr,br,ul,ol,li,sub,sup,div[id|class]',
		'force_p_newlines' => false,
	])); ?>"
	data-url_link="<?php view::attr(fetch::action('link', 'update', null, 'callback={{callback}}')); ?>"
	data-url_image="<?php view::attr(fetch::action('image', 'embed', null, 'viewport=module&task=select&callback={{callback}}')); ?>"
>
	<textarea y-name="textarea"></textarea>

	<script y-name="image" type="text/html">
		<img src="{{ src }}" alt="{{ title }}"/>
	</script>
</span>
