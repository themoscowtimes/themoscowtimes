<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'issue-index') ?>

<?php view::start('main') ?>
<div class="container">
	<h3 class="header--style-3 mb-3">
		<?php view::lang('Print editions'); ?>
	</h3>
</div>
<div class="container">
	<div class="row-flex">
		<div class="col">
			<div class="row-flex">
				<?php foreach ($items as $issue): ?>
					<div class="col-3 col-6-xs mb-3">
						<?php view::file('issue/excerpt/extended', ['item' => $issue, 'issue_nav'=>true]); ?>
					</div>
				<?php endforeach; ?>
				<div class="col-12" y-use="More" data-url="<?php view::route('issues', ['offset' => '{{offset}}']) ?>" data-start="21" data-step="21">
					<div class="align-center">
						<span title="View more issues" class="button mb-3">View more issues</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php view::end(); ?>