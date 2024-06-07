<?php view::asset('js', fetch::base().'vendor/moment/Moment.js'); ?>
<?php
$time = new DateTime();
$offset = (
	timezone_offset_get(new DateTimeZone('Europe/Moscow'), $time)
	- timezone_offset_get(new DateTimeZone(date_default_timezone_get()), $time)
) / 60;
?>
{% if item.data.use_time_publication == 1 %}
	<span y-use="manager.Date" data-date="{{ item.data.time_publication }}" data-offset="<?php view::attr($offset) ?>" data-lang="<?php view::lang() ?>" data-format="ll H:mm"></span>
{% endif %}