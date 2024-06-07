<hr />
<div y-use="manager.form.Campaign">
	<div y-name="create" style="display:none">
		Save the campaign to create advertorials
	</div>

	<div y-name="update" style="display:none">
		<div class="row">
			<div class="col">
				<a y-name="advertorials" href="<?php view::action('campaign', 'advertorials', '{{id}}')?>" class="btn btn-outline-secondary mb-2">View advertorials</a><br />
				<a y-name="advertorial" href="<?php view::action('campaign', 'advertorial', '{{id}}')?>" class="btn btn-outline-secondary mb-2">+ New advertorial</a>
			</div>
		</div>
	</div>
</div>
<hr />