<?php view::file('form/element/dynamic', ['element' => ['images', 'image', 'multiple' => true, 'junction' => [['caption', 'text'], ['credits', 'text']], 'label' => false]]) ?>

<?php view::file('form/element/dynamic', ['element' => ['position', 'select', 'options' => ['column', 'full'], 'label' => false]]) ?>


<script type="text/html" y-name="render">
	<div>
		{% if images.length > 0 %}
			{% each images as image %}
				<img width="100" src="<?php view::action('image','serve','{{ image.id }}', 'preset=manager') ?>" />
			{% endeach %}
		{% else %}
			<?php view::lang('label.add_images', 'block') ?>
		{% endif %}
		<?php view::file('form/element/block/position'); ?>
	</div>
</script>