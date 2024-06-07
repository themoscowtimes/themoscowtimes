<div y-name="live" y-use="manager.form.markup.Live" class="mb-2">

	<a
		class="btn btn-primary"
		href="<?php view::action('live', 'manage', '{{id}}') ?>"
		style="display:none"
		y-name="posts"
	>
		Add or update posts
	</a>
	<a
		class="btn btn-warning text-white"
		y-name="inactive"
	>
		Save article to start adding posts
	</a>
</div>