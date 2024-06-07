<?php view::file('form/element/dynamic', ['element' => ['body', 'textarea', 'label' => false]]) ?>

<?php view::file('form/element/dynamic', ['element' => ['position', 'select', 'options' => ['column', 'outside', 'left', 'right'], 'label' => false]]) ?>

<script type="text/html" y-name="render">
	<quote>
		{% if body %}
			{{{ body }}}
		{% else %}
			 <?php view::lang('label.add_content', 'block') ?>
		{% endif %}
		<br />
		<?php view::file('form/element/block/position'); ?>
	</quote>
</script>