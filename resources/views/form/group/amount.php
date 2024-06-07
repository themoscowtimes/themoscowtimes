<div y-use="donate.Amount">
	<?php foreach($group->elements as $element) {
		view::file('form/element', ['element' => $element->element]);
	} ?>
</div>