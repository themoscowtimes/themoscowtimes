<?php // view::file('form/element/dynamic', ['element' => ['image', 'image', 'label' => false]]) ?>

<?php view::file('form/element/dynamic', ['element' => ['description', 'text', 'label' => fetch::lang('field.description')]]) ?>

<?php view::file('form/element/dynamic', ['element' => ['link', 'link', 'label' => false]]) ?>

<?php if(! isset($position) || $position): ?>
	<?php view::file('form/element/dynamic', ['element' => ['position', 'select', 'options' => ['column', 'outside'], 'label' => false]]) ?>
<?php endif; ?>
<script type="text/html" y-name="render">
	<div>
		{% if description || link.title || link.url %}
			{{ description }}

			{% if link.title || link.url %}
				<br />
				<br />
				{% if typeof link.blank != 'undefined' && link.blank == 1 %}<i class="icon">open_in_new</i> {% endif %}
				{% if typeof link.nofollow != 'undefined' && link.nofollow == 1 %}<i class="icon">block</i> {% endif %}
				{{ link.title }} <i class="icon">forward</i> {{ link.url }}

				<a style="display:none;" href="{{ link.url }}"><img /></a>
			{% endif %}
			<br />
			<br />
			<?php if(! isset($position) || $position): ?>
				<?php view::file('form/element/block/position'); ?>
			<?php endif; ?>
		{% else %}
			<?php view::lang('label.add_content', 'block') ?>
		{% endif %}
	</div>
</script>