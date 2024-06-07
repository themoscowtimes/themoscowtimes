{% if item.data.status == 'edit' %}
	<span style="opacity: 0.3;"><i class="icon icon-sm">visibility_off</i></span>
{% endif %}

{% if new Date(item.data.time_publication).getTime() > new Date().getTime() %}
	<span style="opacity: 0.6;"><i class="icon icon-sm">schedule</i></span>
{% endif %}