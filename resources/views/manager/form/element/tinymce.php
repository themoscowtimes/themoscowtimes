<?php view::asset('js', fetch::base().'vendor/tinymce/Tinymce.js'); ?>

<span
	y-use="manager.form.element.Tinymce"
	y-name="element element-<?php view::attr($element->key) ?> <?php view::attr($element->id); ?>"
	data-key="<?php view::attr($element->key) ?>"
	data-value="<?php view::attr($element->value) ?>"
	data-config="<?php view::attr(json_encode([
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
		'plugins' => 'lists wordcount code image link unlink paste autoresize',

		'toolbar' => 'formatselect bold italic underline  | alignleft aligncenter alignright | bullist numlist | code | image link unlink',
		'block_formats' => 'Paragraph=p;Header=h2;Subheader=h3',
		'image_caption' => true,
		'image_advtab' => true,
		'style_formats' => [],

		'valid_elements'=> 'object[height|width|style|data|type],param[name|value|style],embed[src|type|allowscriptaccess|allowfullscreen|width|height],span[name|value|style|class|id],strong/b,em/i,u/underline,a[href|target|id|class|title|data-id|style],-p[style],h2[style],h3[style],hr[style],br,ul[style],ol[style],li[style],img[src|alt|title|align|width|height|id|class|rel|data-size|data-id|data-enlarge|style],table[style|width|height],tr[width|height],td[width|height|rowspan|colspan],th,tbody,sub,sup,iframe[src|width|height|frameborder|style],pre[style],div[id|class|style],blockquote[class|style],code[class|style],script[src],figure[class],figcaption[class]',
		'extended_valid_elements'=>'script[src|type|language],style[type|media]',
		'valid_children' => '+body[style]',
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
