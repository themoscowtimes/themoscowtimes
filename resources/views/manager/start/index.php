<?php view::extend('template/main'); ?>


<?php view::start('main') ?>
	<div class="content-header" y-name="header-fixed">
		<div class="content-header-1">
			<a class="btn btn-primary" href="<?php view::action('article', 'create') ?>">+ <?php view::lang('title.create', 'article') ?></a>
		</div>
	</div>


	<div class="row m-2">
		<?php foreach($items as $item): ?>
			<div class="col">
				<?php if ($item['items']): ?>
					<h3><?php view::text($item['label']); ?></h3>
					<?php foreach ($item['items'] as $subItem): ?>
						<div class="card mb-2">
							<div class="card-header">
								<a href="<?php view::attr($subItem['href']); ?>">
									<i class="icon"><?php view::text($subItem['icon']) ?></i>
									<?php view::text($subItem['label']); ?>
									<?php if ($subItem['button']): ?>
										<a class="float-right" href="<?php view::attr($subItem['button']['href']); ?>">
											<i class="icon"><?php view::text($subItem['button']['icon']) ?></i>
										</a>
									<?php endif; ?>
								</a>
							</div>
							<div class="card-body">
								<?php view::text($subItem['description']); ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="card mb-2">
						<div class="card-body">
							<a href="<?php view::attr($item['href']); ?>">
								<i class="icon"><?php view::text($item['icon']) ?></i>
								<?php view::text($item['label']); ?>
								<?php if ($item['button']): ?>
									<a class="float-right" href="<?php view::attr($item['button']['href']); ?>">
										<i class="icon"><?php view::text($item['button']['icon']) ?></i>
									</a>
								<?php endif; ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php view::end() ?>