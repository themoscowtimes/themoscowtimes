<?php

if(! isset($label) || ! is_string($label)) {
	// Label not set or not configured, figure it out here

	// What label-types not to display
	$nolabel = isset($nolabel) && is_array($nolabel) ? $nolabel : [];

	// Create a a working label
	if ((isset ($keyword) && $keyword == false) || in_array('keyword', $nolabel)) {
		$label = '';
	} elseif(trim($item->keyword)) {
		$label = fetch::text($item->keyword);
	} else {
		$label = fetch::lang(fetch::section($item));
	}

	// Overwrite it with a more specific label
	if ($item->analysis == '1' && ! in_array('analysis', $nolabel)) {
		$label = fetch::lang('News Analysis');
		$modifier = 'opinion';
	} elseif($item->opinion == '1' && ! in_array('opinion', $nolabel)) {
		$label = fetch::lang('opinion');
		$modifier = 'opinion';
	} elseif($item->indepth == '1' && ! in_array('indepth', $nolabel)) {
		$label = fetch::lang('Feature');
		$modifier = 'indepth';
	} elseif($item->ukraine_war == 1 && ! trim($item->keyword) && ! in_array('ukraine_war', $nolabel)) {
		$label = fetch::lang('Ukraine War');
		$modifier = 'ukraine_war';
	} elseif($item->meanwhile == '1' && ! in_array('meanwhile', $nolabel)) {
		if (isset($context) && $context!='article') {
			$modifier = 'meanwhile';
		}
	} elseif($item->type == 'live' && ! in_array('live', $nolabel)) {
		$label = fetch::lang('Liveblog');
		$modifier = 'liveblog';
	}
}

$bem = fetch::bem('label', $context ?? null, $modifier ?? null);
?>


<?php if($label && !$item->sponsored): ?>
	<span class="<?php view::attr($bem()) ?>">
		<?php view::text($label); ?>
	</span>
<?php endif; ?>