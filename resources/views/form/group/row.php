<?php if(isset($group->label)): ?>
	<label><?php view::text($group->label); ?></label>
<?php elseif(isset($group->name)): ?>
	<label><?php view::lang('element.'.$group->name); ?></label>
<?php endif; ?>
<div class="row-flex">
	<?php view::file('form/group', ['elements' => $group->elements]); ?>
</div>