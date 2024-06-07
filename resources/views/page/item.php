<?php $bem = fetch::bem('page', $context ?? null, $modifier ?? null) ?>
<?php view::extend('template/default'); ?>
<?php view::block('seo', fetch::seo('page', ['item' => $item])) ?>
<?php view::block('body.class', 'page-item') ?>
<?php view::start('main') ?>
<?php view::manager('page', $item->id); ?>
<div class="container">
  <div class="row-flex gutter-2">
    <div class="col">
      <article class="page">
        <header class="page__header ">
          <h1><?php view::text($item->title) ?></h1>
          <h2><?php view::text($item->subtitle) ?></h2>
        </header>
        <?php if ($item->intro != ''): ?>
        <div class="page__intro">
          <?php view::raw(nl2br(strip_tags($item->intro))); ?>
        </div>
        <?php endif; ?>
        <?php if ($item->image): ?>
        <figure class="page__featured-image featured-image">
          <img src="<?php view::src($item->image, 'article_1360') ?>" />
          <?php if ($item->caption!='' || $item->credits!=''): ?>
          <figcaption class="">
            <span class="page__featured-image__caption featured-image__caption">
              <?php view::text($item->caption) ?>
            </span>
            <span class="page__featured-image__credits featured-image__credits">
              <?php view::text($item->credits) ?>
            </span>
          </figcaption>
          <?php endif; ?>
        </figure>
        <?php endif; ?>
        <div class="page__content-container">
          <div class="page__content">
            <?php view::raw($item->body) ?>
          </div>
          <?php view::file('common/social', ['item'=>$item]) ?>
        </div>
      </article>
    </div>
    <div class="col-auto hidden-sm-down">
      <aside class="sidebar" style="">
        <?php if (count($item->files) > 0): ?>
        <section class="sidebar__section">
          <div class="sidebar__section__header">
            <h3 class="header--style-3">Downloads</h3>
          </div>
          <?php foreach($item->files as $file): ?>
          <a class="button button--full-width mb-2" href="<?php view::route('file', ['file' => $file->file]) ?>"
            target="_blank">
            <?php if ($title = $file->junction('title')): ?>
            <?php view::text($title); ?>
            <?php else: ?>
            <?php view::text($file->file); ?>
            <?php endif; ?>
          </a>
          <?php endforeach; ?>
        </section>
        <?php endif; ?>
        <!-- vaste breedte 336 -->
        <section class="sidebar__section">
          <?php $banner = fetch::banner('other_1') ?>
        </section>
        <div class="sidebar__sticky">
          <section class="sidebar__section">
          </section>
        </div>
      </aside>
    </div>
  </div>
</div>

<script>
if (typeof window.freestar === 'object') {
	freestar.config.disabledProducts = {
		stickyFooter: true,
		video: true,
		revolvingRail: true,
		pushdown: true,
		dynamicAds: true,
		superflex: true,
		slidingUnit: true,
		sideWall: true,
		pageGrabber: true,
		googleInterstitial: true,
	};
}
</script>

<?php view::end(); ?>