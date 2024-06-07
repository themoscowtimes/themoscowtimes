<?php view::file('form/element/dynamic', ['element' => ['body', 'tinymce_small', 'label' => false]]) ?>

<script type="text/html" y-name="render">
	<div>
		{% if body %}
			{{{ body }}}
		{% else %}
			 <?php view::lang('label.add_content', 'block') ?>
		{% endif %}
	</div>
</script>