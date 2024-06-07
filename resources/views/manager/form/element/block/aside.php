<?php view::file('form/element/dynamic', ['element' => ['body', 'tinymce_small', 'label' => false]]) ?>

<?php view::file('form/element/dynamic', ['element' => ['position', 'select', 'options' => ['column', 'outside', 'left', 'right'], 'label' => false]]) ?>

<script type="text/html" y-name="render">
	<div>
		{% if body %}
			{{{ body }}}
		{% else %}
			 <?php view::lang('label.add_content', 'block') ?>
		{% endif %}
		<?php view::file('form/element/block/position'); ?>
	</div>
</script>