{% var opacity = 1; %}
{% if status != 'live' %}
	<i class="icon icon-sm">visibility_off</i>&nbsp;&nbsp;
	{% opacity = 0.5; %}
{% endif %}
{% if new Date(time_publication).getTime() > new Date().getTime() %}
	<i class="icon icon-sm">schedule</i>&nbsp;&nbsp;
	{% opacity = 0.5; %}
{% endif %}
<span style="opacity: {{ opacity}}">
	{{ title }}
</span>