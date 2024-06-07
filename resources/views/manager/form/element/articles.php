<?php view::asset('js', fetch::base() . 'vendor/jquery/UI.js'); ?>

<div
	y-use="manager.form.element.Articles"
	y-name="element element-<?php view::attr($element->key) ?>"
	data-key="<?php view::attr($element->key) ?>"
	data-value="<?php view::attr(json_encode($element->value)) ?>"
	data-url_select="<?php view::action($element->module, 'index', null, 'viewport=module&task=select&callback={{callback}}') ?>"
	data-url_update="<?php view::action($element->module, 'update', '{{id}}', 'viewport=item&task=update&callback={{callback}}') ?>"
	data-update="<?php view::attr(fetch::allowed($element->module, 'update') ? 'true' : 'false') ?>"
	data-multiple="<?php view::attr($element->multiple ? 'true' : 'false') ?>"
	data-max="<?php view::attr($element->max ? $element->max : '9999' ) ?>"
 >
	<div class="card bg-light">
		<div class="card-body">
			<div y-name="container"></div>
			<span class="clickable btn btn-primary" y-name="add" style="display: none;"><?php view::lang('label.add'); ?></span>
		</div>
	</div>

	<script type="text/html" y-name="relative">
		<div y-name="relative" data-id="{{ id }}" class="card mb-1">
			<div class="card-body">
				<div class="row">
					<div class="col">
						<?php if ($element->view): ?>
							<?php view::file('form/relation/' . $element->view); ?>
						<?php else: ?>
							<?php view::file('form/relation/title'); ?>
						<?php endif; ?>
					</div>

					<?php if (is_array($element->junction)): ?>
						<span class="col-6">
							<?php foreach ($element->junction as $junction): ?>
								<?php $label = isset($junction['label']) ? $junction['label'] : fetch::lang('field.' . $junction[0]); ?>
								<?php if (! isset($junction[1]) || $junction[1] == 'text'): ?>
									<input type="text" class="form-control mb-1" placeholder="<?php view::attr($label) ?>" y-name="junction" data-name="<?php view::attr($junction[0]) ?>" value="{{ junction.<?php view::attr($junction[0]); ?> }}" />
								<?php elseif (! isset($junction[1]) || $junction[1] == 'textarea'): ?>
									<textarea class="form-control mb-1" placeholder="<?php view::attr($label) ?>" y-name="junction" data-name="<?php view::attr($junction[0]) ?>">{{ junction.<?php view::attr($junction[0]); ?> }}</textarea>
								<?php elseif ($junction[1] == 'select'): ?>
									<select class="form-control mb-1" y-name="junction" data-name="<?php view::attr($junction[0]) ?>">
										<option><?php view::text($label); ?></option>
										<?php foreach($junction['options'] as $option => $label): ?>
											<?php if(is_int($option)) {
												$option = $label;
												$label = fetch::lang('option.' . $junction[0] . '.' . $option);
											} ?>
											<option {% if junction.<?php view::attr($junction[0]); ?> == "<?php view::attr($option) ?>" %}selected="selected"{% endif %} value="<?php view::attr($option); ?>"><?php view::text($label); ?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
							<?php endforeach; ?>
						</span>
					<?php endif; ?>

					<div class="col-3">
						<div class="float-right">
							<a y-name="update" href="#" title="<?php view::lang('label.update') ?>" ><i class="icon">edit</i></a>
							<a y-name="delete" href="#" title="<?php view::lang('label.delete') ?>" ><i class="icon">delete</i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</script>
</div>