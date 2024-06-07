<?php view::extend('template/default'); ?>

<?php view::block('body.class', 'authors') ?>

<?php view::start('main') ?>

<div class="container">
	<div class="cluster__header mb-3">
		<h2 class="cluster__label header--style-3">Our Authors</h2>
	</div>
</div>
<div class="container">
	<div class="row-flex">
		<div class="col">
			<select
				class="authors-index authors-index__nav"
				onchange="document.location.href=this.value"
			>
				<option value="<?php view::text($index); ?>">Selected: <?php view::text($index); ?></option>
				<?php
					$chars = range('A', 'Z');
					foreach($chars as $char) {
						view::raw('<option value="'.$char.'">'.$char.'</option>');
					}
				?>
			</select>
			<?php if(isset($authors)): ?>
				<ul class="authors-index authors-index__list">
				<?php foreach($authors as $author): ?>
					<li>
						<a
							href="<?php view::route('author', ['slug' => $author->slug]) ?>"
							title="<?php view::attr($author->title) ?>"
						>
							<?php view::text($author->title); ?>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="col-auto">
			<aside class="sidebar" style="">
				<section class="sidebar__section">
				</section>
			</aside>
		</div>
	</div>
</div>

<?php view::end(); ?>