define('contribute.Bar')
  .use('Cookie')
  .as(function(y, Cookie) {
    this.start = function(scope) {
      var windowHeight = y(window).height();
      var scrollTrigger = (windowHeight * 2) / 3;

      y(window).scroll(function() {
        if (Cookie.get('contribute-cta') !== 'suspended') {
          if (y(window).scrollTop() > scrollTrigger) {
            scope.addClass('contribute-bar--show');
          }
        }
      });

      scope.fetch('contributeLater').click(function() {
        Cookie.set('contribute-cta', 'suspended', '3.5');
        scope.removeClass('contribute-bar--show');
      });

    }
  });