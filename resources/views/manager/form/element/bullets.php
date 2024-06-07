<div
	y-use="manager.form.element.Bullets"
	y-name="element element-<?php view::attr($element->key) ?> <?php view::attr($element->id); ?>"
	data-key="<?php view::attr($element->key) ?>"
	data-value="<?php view::attr(json_encode($element->value)) ?>"
 >

	<div class="card bg-light">
		<div class="card-body">
			<span class="clickable btn btn-primary mb-2" y-name="add"><?php view::lang('label.add'); ?></span>
			<div y-name="container"></div>
		</div>
	</div>

	<script type="text/html" y-name="bullet">
		<div class="row mb-2" y-name="bullet">
			<div class="col-10"><input class="form-control" y-name="text" maxlength="85" type="text" /></div>
			<div class="col-2"><a class="float-right" y-name="delete" href="#" title="<?php view::lang('label.delete') ?>"><i class="icon">delete</i></span></div>
		</div>
	</script>
</div>