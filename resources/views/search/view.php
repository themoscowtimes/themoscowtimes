<?php view::extend('template/default'); ?>
<?php view::block('body.class', 'article-index') ?>
<?php view::start('main') ?>
<div class="container">
  <div class="row-flex">
    <div class="col">
      <section class="cluster cluster--section-articles mb-4">
        <?php if(isset($_GET['archive'])): ?>
        <div class="cluster__header">
          <h2 class="cluster__label header--style-3"><?php view::lang('Found archived articles'); ?> with
            "<?php view::text($query); ?>"</h2>
        </div>
        <div class="row-flex">
          <?php foreach ($archive as $article): ?>
          <div class="col-4">
            <?php view::file('article/excerpt/default', ['item' => $article, 'date' => true, 'archive' => true]); ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="cluster__header">
          <h2 class="cluster__label header--style-3"><?php view::lang('Found articles'); ?> with
            "<?php view::text($query); ?>"</h2>
        </div>
        <div class="row-flex">
          <?php foreach ($articles as $article): ?>
          <div class="col-4">
            <?php view::file('article/excerpt/default', ['item' => $article, 'date' => true]); ?>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
    <div class="col-auto">
      <aside class="sidebar" style="">
        <section class="sidebar__section">
          <?php if(count($authors) > 0): ?>
          <div class="sidebar__section__header">
            <h3 class="header--style-3">See articles by these authors</h3>
          </div>
          <?php endif; ?>
          <?php foreach ($authors as $author): ?>
          <?php view::file('author/excerpt/default', ['item' => $author, 'link' => true]); ?>
          <?php endforeach; ?>
        </section>
        <section class="sidebar__section">
          <?php view::banner('section_1') ?>
        </section>
      </aside>
    </div>
  </div>
</div>
<?php view::end(); ?>