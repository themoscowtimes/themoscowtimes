<?php //view::asset('css', fetch::base(). 'css/manager-content.css'); ?>

<div
	class="row"
	y-use="manager.form.element.Home"
	data-url="<?php view::url(fetch::config('preview')) ?>"
>
	<div class="col-3">

		<?php //foreach($group->elements as $element): ?>
			<?php view::file('form/group', ['elements' => $group->elements]); ?>
		<?php // endforeach; ?>
	</div>

	<div class="col-9">
		<iframe y-name="preview" name="preview-inline" height="8000" width="1400" border="none" style="border: 0;"></iframe>
		<div  style="position: absolute; top: 0; height: 8000px; width: 98%"></div>
	</div>
</div>