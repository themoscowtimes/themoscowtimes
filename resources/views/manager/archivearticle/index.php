<?php view::extend('template/' . fetch::viewport() ); ?>

<?php view::start('main') ?>
	<div
		y-use="manager.Index"
		y-name="index"
		data-url="<?php view::action('archivearticle', 'items', null, 'count={{count}}&amount={{amount}}&skip={{skip}}&sort={{sort}}&filter={{filter}}&search={{search}}'); ?>"
		data-filter="[]"
		data-filters="<?php
		view::attr(json_encode([
			'day' => null,
			'author' => null,
		]));
		?>"
		data-sort="<?php view::attr(json_encode(fetch::state('sort', []))); ?>"
		data-search="<?php view::attr(fetch::state('search', '')); ?>"
		data-skip="<?php view::attr(fetch::state('skip', '0')); ?>"
		data-amount="<?php view::attr(fetch::config('pagination', false)); ?>"
		data-sortable="<?php view::attr(fetch::config('sortable', false) ? 'true' : 'false'); ?>"
		data-tree="false"
		data-lock="<?php view::attr(fetch::config('lock', false) ? 'true' : 'false'); ?>"
		data-url_locked="<?php view::action('archive', 'locked'); ?>"
	>
		<script type="text/html" y-name="filter">
			<div class="input-group input-group filter w-25 mr-2 float-left" data-load="true" y-use="manager.index.filter.{{ name == 'author' ? 'Author' : 'Date' }}">
				<div y-name="filter">
					<div class="input-group-prepend clickable" y-name="remove" style="display:none">
						<div class="input-group-text">
							<i class="icon icon-sm">close</i>
						 </div>
					</div>
					{% if name == 'day' %}
						<input  class="form-control" type="date" y-name="input" />
						<input type="hidden" y-name="select" name="{{ name }}" />
					{% endif %}

					{% if name == 'author' %}
						<input class="form-control" type="text" placeholder="Author" value="" y-name="input" />
						<input type="hidden" y-name="select" name="{{ name }}" value="-1" />
					{% endif %}
				</div>
			</div>
		</script>

		<?php view::file('index/search') ?>

		<?php view::file('index/table', ['batch' => $batch]); ?>

		<?php view::file('index/pagination') ?>

		<div class="content-header" y-name="header-fixed">

			<div class="content-header-1">
				<div class="row ">
					<div class="col-3">
						<h2 class="float-left"><?php view::lang('name'); ?></h2>
					</div>
					<div class="col-9">
						<div class="float-right w-25" y-name="search"></div>
					</div>
				</div>
			</div>

			<div class="content-header-2">
				<div class="row">
					<div class="col" y-name="filters"></div>
					<div class="col-2">
						<div class="float-right" y-name="pagination"></div>
					</div>
				</div>
			</div>
		</div>


		<div class="content-main">
			<div class="" y-name="list"></div>
		</div>
	</div>
<?php view::end(); ?>