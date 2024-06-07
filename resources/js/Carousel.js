define("Carousel").as(function(y) {
  this.start = function(scope) {
    var nextBtn = scope.fetch("next");
    var prevBtn = scope.fetch("prev");

    if (scope.fetch("pane").length > 1) {
      // hide all
      scope.fetch("pane").hide();

      // show start slide
      var current = scope.fetch("pane").first();
      current.show();
      var hold = false,
        counter = 0;
      // var clicked = false;

      var intervalID = setInterval(slideOnLoad, 100);

      function carouselToggles(toggleType) {
        return function(e) {
          e.preventDefault();
          var slide = current.fetch("pane", toggleType);
          var type =
            toggleType === "prev"
              ? scope.fetch("pane").last()
              : scope.fetch("pane").first();
          if (slide.length <= 0) slide = type;
          current.fadeOut(0, function() {
            slide.fadeIn(0);
          });
          current = slide;
          if (intervalID && !hold) {
            hold = true;
            return clearInterval(intervalID);
          }
        };
      }

      function slideOnLoad() {
        if (!hold) {
          counter++;
        }
        if (counter > 60) {
          counter = 0;
          // get next
          var next = current.fetch("pane", "next");
          // if there is no next, use first
          if (next.length <= 0) {
            next = scope.fetch("pane").first();
          }
          current.fadeOut(function() {
            next.fadeIn();
          });
          current = next;
        }
      }

      prevBtn.click(carouselToggles("prev"));

      nextBtn.click(carouselToggles("next"));

      /*
			scope.fetch('pane').mouseover(function(){
				hold = true;
			});
			*/
      scope.fetch("pane").click(function() {
        hold = true;
        //clicked = true;
      });

      /*
			scope.fetch('pane').mouseout(function(){
				if(! clicked) {	
					//hold = false;
				}
			});
			*/
      var height = 0;
      scope.fetch("pane").each(function() {
        if (y(this).outerHeight() > height) {
          height = y(this).outerHeight();
        }
      });
      scope.height(height);
    }
  };
});

define('CarouselAmbassadors').as(function(y) {
  this.start = function(scope) {
    const settings = {
      infinite: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      lazyLoad: "progressive",
      autoplay: true,
      autoplaySpeed: 5000,
      arrows: true,
      appendArrows: $(".carousel-toggles-counter"),
      nextArrow: '<a class="slick-next slick-arrow"></a>',
      prevArrow: '<a class="slick-prev slick-arrow"></a>'
    };

    y(scope).on('init', function(e, slider, index) {
      $('.slick-prev').after(
        '<div class="slide-counter"><p class="active-slide-counter">1</p><p>/' +
        slider.slideCount +
        '</p></div>'
      );
    });

    y(scope).slick(settings);

    y(scope).on('afterChange', function(e, slider, cIndex) {
      $('.active-slide-counter').html(cIndex + 1);
    });
  };
});
