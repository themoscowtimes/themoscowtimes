<?php if ($size == 'sm'): ?>
	<div class="contribute-teaser-mobile hidden-sm-up">
		<div class="contribute-teaser-mobile__cta">
			<span>Support The Moscow Times!</span>
		</div>
		<div class="contribute-teaser-mobile__container">
			<a class="contribute-teaser-mobile__container__button"
			   href="<?php view::route('contribute'); ?>?utm_source=contribute&utm_medium=internal-header-mobile"
			   class="contribute-teaser__cta">Contribute today</a>
		</div>

	</div>
<?php else: ?>
	<div class="site-header__contribute contribute-teaser hidden-xs">
		<div class="contribute-teaser__cta mb-1">Support The Moscow Times!</div>
		<a class="contribute-teaser__button"
		   href="<?php view::route('contribute'); ?>?utm_source=contribute&utm_medium=internal-header"
		   class="contribute-teaser__cta">Contribute today</a>
	</div>
<?php endif; ?>