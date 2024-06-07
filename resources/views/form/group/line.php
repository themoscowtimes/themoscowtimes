<?php
$col = floor(12 / (count($group->elements) > 0 ? count($group->elements) :  1));
?>

<div class="row-flex">
	<?php foreach($group->elements as $element) {
		view::raw('<div class="col-' . $col . '">');
		if($element->type === 'group'){
			view::file('form/group/' . $element->group->type, ['group' => $element->group]);
		} elseif($element->type === 'markup') {
			view::file('form/markup/' . $element->markup->type, ['markup' => $element->markup]);
		} else {
			view::file('form/element', ['element' => $element->element]);
		}
		view::raw('</div>');
	} ?>
</div>