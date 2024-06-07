define("Slider").as(function(y) {
  this.start = function(scope) {
    if (scope.fetch("slide").length > 1) {
      // hide all
      scope.fetch("slide").hide();

      // show start slide
      var current = scope.fetch("slide").first();
      current.show();

      var counter = 0;
      var hold = true;
      var sliderInterval = setInterval(function() {
        if (!hold) {
          counter++;
        }
        if (counter > 200) {
          counter = 0;
          // get next
          var next = current.fetch("slide", "next");
          // if there is no next, use first
          if (next.length <= 0) {
            next = scope.fetch("slide").first();
          }
          /*
					// navigation buttons
					scope.fetch('.yf-slide-button-'+current.data('id')).removeClass('active');
					scope.fetch('.yf-slide-button-'+next.data('id')).addClass('active');
					*/
          current.fadeOut();
          next.fadeIn();
          current = next;
        }
      }, 50);

      scope.fetch("next").click(function() {
        // reset counter
        counter = 0;
        // get next
        var next = current.fetch("slide", "next");
        // if there is no next, use first
        if (next.length <= 0) {
          next = scope.fetch("slide").first();
        }
        current.fadeOut();
        next.fadeIn();
        current = next;
        return false;
      });

      scope.fetch("previous").click(function() {
        // reset counter
        counter = 0;
        // get next
        var next = current.fetch("slide", "prev");
        // if there is no next, use first
        if (next.length <= 0) {
          next = scope.fetch("slide").last();
        }
        current.fadeOut();
        next.fadeIn();
        current = next;
        return false;
      });

      scope.fetch("slide").mouseover(function() {
        hold = true;
      });

      scope.fetch("slide").mouseout(function() {
        hold = false;
      });

      // r sete inline slider aspect ratio to first slider image aspect ratio

      if (scope.data("fixed-size") == true) {
        var firstImageHeight = scope.find("img").height();
        var firstImageWidth = scope.find("img").width();

        var aspecRatio = firstImageHeight / firstImageWidth;

        var paddingBottom = aspecRatio * 100;

        // scope.css("padding-bottom", paddingBottom + "%");
        // scope.css("height", 0);
      } else {
        scope.css("height", current.height());
      }
    } else {
      scope.fetch("navigation").hide();
    }
  };
});
