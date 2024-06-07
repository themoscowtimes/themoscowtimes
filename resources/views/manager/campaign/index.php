<?php view::extend('index') ?>

<?php view::start('filters') ?>
	<div class="w-25 mr-2 float-left">
		<select class="form-control" onchange="document.location.href = this.value">
			<option value="<?php view::action('campaign', fetch::module() . 's', 0) ?>">Select a campaign</option>
			<?php foreach ($campaigns as $campaign): ?>
				<option <?php view::attr(isset($active) && $active == $campaign->id ? 'selected="selected"' : '') ?> value="<?php view::action('campaign', fetch::module() . 's', $campaign->id) ?>">
					<?php view::text($campaign->title) ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
<?php view::end() ?>