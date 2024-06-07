<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'location-index') ?>

<?php view::start('main') ?>

<div class="container">
	<div class="row-flex">
		<div class="col" >
			<div class="item-header">
				<h3 class="header--style-3"><?php view::lang('Places') ?></h3>
			</div>
		</div>
	</div>
	<div class="row-flex">
		<div class="col" >
			<div class="row-flex">
				<div class="col-auto">
					<div class="list-filter" >

						<div y-use="Locations">
							<select y-name="type">
								<option value="">Event type</option>
								<option value="theater">Theater</option>
								<option value="movie">Movie</option>
								<option value="concert">Concert</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col">

					<?php foreach ($items as $item): ?>
						<div class="mb-3">
							<div y-name="location" data-type="<?php view::attr($item->type) ?>">
								<?php view::file('location/excerpt/horizontal', ['item' => $item]); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="col-auto">
			<aside class="sidebar" style="">
				<section class="sidebar__section">
					<div class="banner" style="width: 336px; height: 500px; border: 1px solid black;">
						AD
					</div>
				</section>
				
			</aside>
		</div>
	</div>
</div>
<?php view::end(); ?>