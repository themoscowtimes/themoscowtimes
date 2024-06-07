<div
	y-use="manager.form.element.BannerPositions"
	y-name="element element-<?php view::attr($element->key) ?> <?php view::attr($element->id); ?>"
	data-key="<?php view::attr($element->key) ?>"
	data-value="<?php view::attr(json_encode($element->value)) ?>"
>

All screen sizes above 1024 px<br />
Make sure banner is suitable for the spot<br />
   <?php foreach ([
	   'home_top',
	   'home_1',
	   'home_2',
	   'home_3',
	   'home_4',
	   'home_5',

	   'article_top',
	   'article_body',
		 'article_1',
		 'article_sidebar_bottom',
		 'article_2',
		 'sticky_article_billboard_bottom',

	   'section_top',
	   'section_1',

	   'event_top',
	   'event_1',
	   'event_2',

	   'advertorial_top',
	   'advertorial_1',
	   'advertorial_2',

	   'other_top',
	   'other_1',
	   'other_2',
   ] as $position): ?>
	   <input type="checkbox" y-name="checkbox" value="<?php view::attr($position); ?>" <?php view::raw(is_array($element->value) && in_array($position, $element->value) ? 'checked="checked"' : ''); ?> /> <?php view::lang('option.positions.' . $position) ?><br />
	<?php endforeach; ?>
<hr/>
	Mobile devices<br />
	Make sure this banner is suitable for mobile width (max 336 px)<br />
   <?php foreach ([
		'home_1_mobile',
		'home_3_mobile',
		'home_5_mobile',
	  'article_body_mobile',
		'article_body_amp',
		'article_2_mobile',
		'event_2_mobile',
		'advertorial_2_mobile',
		'other_2_mobile',
   ] as $position): ?>
	   <input type="checkbox" y-name="checkbox" value="<?php view::attr($position); ?>" <?php view::raw(is_array($element->value) && in_array($position, $element->value) ? 'checked="checked"' : ''); ?> /> <?php view::lang('option.positions.' . $position) ?><br />
	<?php endforeach; ?>
</div>