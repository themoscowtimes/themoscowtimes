<div y-use="donate.Period">
	<?php foreach($group->elements as $element) {
		view::file('form/element', ['element' => $element->element]);
	} ?>
</div>