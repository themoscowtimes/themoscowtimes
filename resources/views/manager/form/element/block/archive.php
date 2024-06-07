<?php view::file('form/element/dynamic', ['element' => ['article', 'relation', 'module' => 'archivearticle', 'max' => 1, 'label' => false]]) ?>

<?php if(! isset($position) || $position): ?>
	<?php view::file('form/element/dynamic', ['element' => ['position', 'select', 'options' => ['column', 'outside'], 'label' => false]]) ?>
<?php endif; ?>

<script type="text/html" y-name="render">
	<div>
		{% if article %}
			{{{ article.title }}}
			<?php if(! isset($position) || $position): ?>
				<?php view::file('form/element/block/position'); ?>
			<?php endif; ?>
			<a style="display:none;" href="<?php view::url('home') ?>"><img /></a>
		{% else %}
			<?php view::lang('label.add_content', 'block') ?>
		{% endif %}
	</div>
</script>