// TODO: GA onScroll
define("home.SidebarCarousel")
	.as(function (y) {
		this.start = function (scope) {
			const settings = {
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				lazyLoad: "progressive",
				autoplay: true,
				autoplaySpeed: 5000,
				arrows: true,
				appendArrows: y(".carousel-sidebar"),
				prevArrow:
					'<a class="carousel__prev carousel__arrow" y-name="arrow"><svg viewBox="0 0 256 256"><polyline fill="none" stroke="white" stroke-width="20" stroke-linejoin="round" stroke-linecap="round" points="184,16 72,128 184,240" /></svg></a>',
				nextArrow:
					'<a class="carousel__next carousel__arrow" y-name="arrow"><svg viewBox="0 0 256 256"><polyline fill="none" stroke="white" stroke-width="20" stroke-linejoin="round" stroke-linecap="round" points="72,16 184,128 72,240" /></svg></a>',
			};

			const settingsMobile = {
				...settings,
				arrows: false,
				// dots: true,
			};

			y(scope).each(function (i, elem) {
				const condition = y(this).hasClass("mobile-carousel")
					? settingsMobile
					: settings;
				y(this)
					.slick(condition)
					.hover(
						function () {
							scope.fetch("arrow").css("visibility", "visible");
						},
						function () {
							scope.fetch("arrow").css("visibility", "hidden");
						}
					);
				y(this).on("beforeChange", function (slick, currSlide) {
					const slide = y(currSlide.$slides.get(currSlide.currentSlide));
					const { title } = slide.fetch("carousel-pane").data();
				});
			});
		};
	});
