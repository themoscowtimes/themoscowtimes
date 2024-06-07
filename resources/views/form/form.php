<form
	action="<?php view::attr($form->attribute('action')); ?>"
	method="<?php view::attr($form->attribute('method')); ?>"
	enctype="<?php view::attr($form->attribute('enctype')); ?>"
>
	<?php view::file('form/group', ['elements' => $form->layout()]); ?>
</form>