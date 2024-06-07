<?php view::file('form/element/dynamic', ['element' => ['image', 'image', 'junction' => [['caption', 'text'], ['credits', 'text'], ['url', 'text']], 'label' => false]]) ?>

<?php if(! isset($position) || $position): ?>
	<?php view::file('form/element/dynamic', ['element' => ['position', 'select', 'options' => ['column', 'outside', 'full', 'left', 'right'], 'label' => false]]) ?>
<?php endif; ?>
<script type="text/html" y-name="render">

	<div>
		{% if image && image.id && image.id != 0 %}
			<img style="width: 640px" alt="image" src="<?php view::action('image','serve','{{ image.id }}', 'preset=article_640') ?>" />
			{% if image.junction && image.junction.url %}
				<br />
				<small>
					<a href="{{ image.junction.url }}" target="_blank">{{ image.junction.url }}</a>
				</small>
			{% endif %}
		{% else %}
			 <?php view::lang('label.add_image', 'block') ?>
		{% endif %}
		<?php if(! isset($position) || $position): ?>
			<?php view::file('form/element/block/position'); ?>
		<?php endif; ?>
	</div>
</script>