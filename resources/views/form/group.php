<?php foreach($elements as $element) {
	if($element->type === 'group'){
		view::file('form/group/'.$element->group->type, ['group' => $element->group]);
	} elseif($element->type === 'markup') {
		view::file('form/markup/'.$element->markup->type, ['markup' => $element->markup]);
	} else {
		view::file('form/element', ['element' => $element->element]);
	}
} ?>