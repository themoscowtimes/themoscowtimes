<div class="identity" aria-label="[[account]]">
	<a y-name="signin" href="<?php view::route('dashboard') ?>" class="identity__signin">
		<i class="fa fa-user-circle-o"></i>
	</a>


	<div y-name="account" class="identity__account" style="display:none">
		<div class="identity__letter" href="<?php view::route('dashboard') ?>" y-name="letter"></div>
		<div y-name="menu" class="identity__menu" style="display:none">
			<a class="identity__menu__item identity__dashboard" href="<?php view::route('dashboard') ?>"><?php view::lang('My account'); ?></a>
			<a class="identity__menu__item identity__signout" href="<?php view::route('signout') ?>"><?php view::lang('Signout'); ?></a>
		</div>
	</div>
</div>