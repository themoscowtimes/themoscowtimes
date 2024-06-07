<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="googlebot" content="noarchive">

  <base href="<?php view::url('base'); ?>" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#5882b5">
  <link rel="shortcut icon" href="<?php view::url('static'); ?>img/icons/favicon.ico">

  <link rel="publisher" href="https://plus.google.com/114467228383524488842" />

  <link rel="apple-touch-icon-precomposed" sizes="152x152"
    href="<?php view::url('static'); ?>img/icons/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144"
    href="<?php view::url('static'); ?>img/icons/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon-precomposed" sizes="120x120"
    href="<?php view::url('static'); ?>img/icons/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114"
    href="<?php view::url('static'); ?>img/icons/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon-precomposed" sizes="76x76"
    href="<?php view::url('static'); ?>img/icons/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72"
    href="<?php view::url('static'); ?>img/icons/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon-precomposed" href="<?php view::url('static'); ?>img/icons/apple-touch-icon-57x57.png">

  <meta property="og:site_name" content="The Moscow Times" />

  <meta property="fb:admins" content="1190953093,691361317" />
  <meta property="fb:app_id" content="1446863628952411" />

  <meta name="twitter:site" content="@MoscowTimes">
  <meta name="twitter:creator" content="@MoscowTimes">
  <meta property="twitter:account_id" content="19527964">
  <meta name="twitter:card" content="summary_large_image"> <!-- or summary -->

  <?php view::block('seo', '<title>The Moscow Times</title>') ?>

  <!-- load stylesheets -->
  <link type="text/css" href="<?php view::url('static'); ?>css/main.css?v=<?php view::version() ?>" rel="stylesheet"
    media="screen" />
  <!-- Other CSS assets -->
  <?php foreach(view::assets('css') as $asset): ?>
  <link type="text/css" href="<?php view::attr($asset) ?>" rel="stylesheet" media="screen" />
  <?php endforeach; ?>

  <link rel="dns-prefetch" href="//www.google-analytics.com" />

  <script type="application/ld+json">
  {
    "@context": "http://schema.org",
    "@type": "NewsMediaOrganization",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "RU",
      "addressLocality": "Moscow",
      "postalCode": "",
      "streetAddress": ""
    },
    "name": "The Moscow Times",
    "email": "general@themoscowtimes.com",
    "telephone": "",
    "url": "https://themoscowtimes.com",
    "logo": "<?php view::url('static'); ?>img/logo_1280.png"
  }
  </script>

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "https://www.themoscowtimes.com/",
  <?php /*  "potentialAction": {
      "@type": "SearchAction",
      "target": "https://www.themoscowtimes.com/{search_term_string}",
      "query-input": "required name=search_term_string"
    } */ ?>
  }
  </script>
  <?php view::settings('head') ?>
</head>


<body class="<?php view::block('body.class', '') ?>" y-use="Main">
  <?php view::settings('body_before') ?>

  <?php view::block('body',''); ?>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
  <script src="<?php view::url('static') ?>vendor/jquery/Timeago.js"></script>
  <script src="<?php view::url('static') ?>vendor/jquery/Lightbox.js"></script>
  <script src="<?php view::url('static') ?>vendor/jquery/fitvids.js"></script>

  <!-- Other JS assets -->
  <?php foreach(view::assets('js') as $asset): ?>
  <script src="<?php view::attr($asset) ?>"></script>
  <?php endforeach; ?>

  <div y-name="viewport" class="hidden-lg-down" data-viewport="xl"></div>
  <div y-name="viewport" class="hidden-md-down hidden-xl" data-viewport="lg"></div>
  <div y-name="viewport" class="hidden-lg-up hidden-sm-down" data-viewport="md"></div>
  <div y-name="viewport" class="hidden-md-up hidden-xs" data-viewport="sm"></div>
  <div y-name="viewport" class="hidden-sm-up" data-viewport="xs"></div>

  <script type="text/javascript" src="<?php view::url('static') ?>vendor/yellow/Yellow.js"
    <?php if(fetch::env('env') === 'development'): ?> data-main="<?php view::url('base') ?>js.php?rebuild=main"
    data-src="<?php view::url('base') ?>js.php?build=main&file=" <?php else: ?>
    data-main="<?php view::url('static') ?>js/main.js?v=<?php view::version() ?>"
    data-src="<?php view::url('static') ?>js/" <?php endif; ?>
    data-console="<?php view::attr(fetch::env('env') === 'development' ? '1' : '0'); ?>"></script>

  <?php view::settings('body_after') ?>
</body>

</html>
