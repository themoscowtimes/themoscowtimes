<div
	y-use="manager.form.element.TimePublication"
	y-name="element element-<?php view::attr($element->key) ?> <?php view::attr($element->id); ?>"
	data-key="<?php view::attr($element->key) ?>"
	data-value="<?php view::attr($element->value) ?>"
 >

	<?php if (! $element->value): ?>
		<input y-name="toggle" type="checkbox" checked="checked"/> Same as publication time
		<span y-name="time" style="display:none">
	<?php endif; ?>
		<?php view::file('form/element/dynamic', ['element' => ['time', 'date', 'time' => true, 'timezone' => $element->timezone, 'value' => $element->value, 'label' => false]]) ?>
	<?php if (! $element->value): ?>
		</span>
	<?php endif; ?>
</div>