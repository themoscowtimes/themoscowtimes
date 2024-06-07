<?php view::extend('template/default'); ?>
<?php view::block('body.class', 'page-item') ?>
<?php view::start('main') ?>
<div class="container">
  <div class="row-flex gutter-2">
    <div class="col">
      <article class="page">
        <header class="page__header ">
          <h1>Oops!</h1>
          <h2>We couldn't find your page.</h2>
        </header>
        <div class="page__intro">
          The page you were looking for doesn't exist (anymore). Use the search bar or <a href="/"
            title="<?php view::lang('Home'); ?>">get back to the homepage</a>
        </div>
      </article>
    </div>
  </div>
</div>
<?php view::end(); ?>