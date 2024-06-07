<?php
	$from = new DateTime('2023-03-29', new DateTimeZone('Europe/Moscow'));
	$now = new DateTime('now', new DateTimeZone('Europe/Moscow'));
	$interval = $from->diff($now);
	$days = $interval->days;
?>

<!-- Sidebar Carousel -->
<section class="sidebar__section">
	<div class="carousel carousel-sidebar <?php view::raw($class); ?>" y-use="home.SidebarCarousel">

		<div
			y-name="carousel-pane"
			class="carousel__pane "
			data-title="<?php view::text($days); ?> Days since former MT reporter Evan Gershkovich was arrested in Russia"
		>
			<a class="carousel__days" href="<?php view::url('base'); ?>page/journalist-evan-gershkovich-is-in-russian-prison-for-doing-his-job-he-must-be-freed"  title="Evan Gershkovich">
				<img class="carousel__days__background" src="https://static.themoscowtimes.com/image/article_640/e4/000_33CB7XC-2.jpg" alt="Evan Gershkovich" loading="lazy"/>
				<div class="carousel__days__content">
					<div class="carousel__days__content__title mb-2">
						<span class="carousel__days__content__title__highlight">Evan Gershkovich's days in Russian prison</span>
					</div>				
					<div class="carousel__days__content__count mb-3">
						<span class="carousel__days__content__count__highlight"><?php view::text($days); ?></span>
					</div>
				</div>
			</a>
		</div>
		<?php foreach($slides as $slide): ?>
		<div
			y-name="carousel-pane"
			class="carousel__pane"
			data-title="<?php view::text($slide->article->title); ?>"
		>
			<div class="sidebar__section__header">
				<p class="sidebar__section__label header--style-3"><?php view::lang($slide->title); ?></p>
			</div>
			<?php view::file('article/excerpt/default', ['item' => $slide->article]); ?>
		</div>
		<?php endforeach; ?>
	</div>
</section>