define("Navigation").as(function(y) {
  this.start = function(scope) {
    scope.fetch("open").click(function() {
      scope.fetch("expanded").show();
      $("body").addClass("nav-expanded");
    });

    scope.fetch("close").click(function() {
      scope.fetch("expanded").hide();
      $("body").removeClass("nav-expanded");
    });

    y(window).on("scroll", function() {
      window.scrollY > scope.offset().top
        ? scope.find(".sticky-nav").addClass("stick-menu")
        : scope.find(".sticky-nav").removeClass("stick-menu");
    });

    /*
		var buttonHeight = scope.fetch('open').offset().top;
		scope.fetch('container').css('margin-top',buttonHeight);
		*/
  };
});
