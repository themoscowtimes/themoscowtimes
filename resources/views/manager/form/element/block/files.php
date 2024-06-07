<?php view::file('form/element/dynamic', ['element' => ['files', 'file', 'multiple' => true, 'junction' => [['title', 'text']], 'label' => false]]) ?>

<script type="text/html" y-name="render">
	<div>
		{% if files.length > 0 %}
			{% each files as file %}
				{{ file.file }}
			{% endeach %}
		{% else %}
			<?php view::lang('label.add_files', 'block') ?>
		{% endif %}
	</div>
</script>