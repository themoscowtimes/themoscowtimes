<div class="contribute-wrapper">
	<?php view::file('form/group', ['elements' => $group->elements]); ?>
	<br />
	<small style="font-size: 0.8rem; line-height: 1em;">
		You can cancel or change your contribution at any time by logging into <a style="color: #3263c0;"
			href="<?php view::url('base'); ?>account/signin">your personal account.</a>
	</small>
</div>