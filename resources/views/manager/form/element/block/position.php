{%
var lang = {
	full : '<?php view::lang('option.position.full') ?>',
	column : '<?php view::lang('option.position.column') ?>',
	left : '<?php view::lang('option.position.left') ?>',
	right : '<?php view::lang('option.position.right') ?>',
	outside : '<?php view::lang('option.position.outside') ?>',
}
%}
{% if position %}
	<br />
	<small>
		{{ lang[position] }}
	</small>
{% endif %}
