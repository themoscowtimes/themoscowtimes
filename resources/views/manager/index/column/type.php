{% if item.data.type == 'live' %}
	<a href="<?php view::action('live', 'manage', '{{item.data.id}}') ?>">Liveblog</a>
{% else %}
	{{ item.data.type }}
{% endif %}