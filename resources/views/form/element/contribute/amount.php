<div class="contribute__amount" y-name="amount <?php view::attr($element->key) ?>" style="display:none">
	<div class="rw-contribute">
		<?php foreach ($element->options as $option): ?>
			<div class="clmn-contribute">
				<a class="contribute__amount__option" id="<?php view::attr($element->key) ?>-<?php view::attr($option) ?>" y-name="option option_<?php view::attr($option) ?>" data-amount="<?php view::attr($option) ?>"><?php view::text(is_numeric($option) ? ('$' . $option) : $option) ?></a>
			</div>
		<?php endforeach; ?>
	</div>
	<input class="mt-3" type="text" y-name="other" placeholder="Other amount"/>
</div>