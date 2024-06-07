<div y-use="article.Newsletter">
	<script type="text/html" y-name="newsletter">
		<div class="article__newsletter">
			<h6 class="label newsletter__label"><?php view::lang('Sign up for our weekly newsletter'); ?></h6>
			<div y-use="Newsletter" data-url="<?php view::route('newsletter'); ?>" id="newsletter">
				<input type="text" placeholder="<?php view::lang('Your email'); ?>" y-name="email" class="mb-1" />
				<button class="button button--color-2" y-name="submit"><?php view::lang('Submit'); ?></button>
				<div class="" y-name="error" style="display:none"></div>
				<div class="" y-name="done" style="display:none">Thanks for signing up!</div>
			</div>
		</div>
	</script>
</div>