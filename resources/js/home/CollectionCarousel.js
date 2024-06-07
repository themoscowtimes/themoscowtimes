define("home.CollectionCarousel")
	.as(function (y) {
		this.start = function (scope) {
			const settings = {
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				lazyLoad: "progressive",
				autoplay: true,
				autoplaySpeed: 10000,
				arrows: true,
				appendArrows: "",
				prevArrow: "",
				nextArrow: "",
			};

			const settingsMobile = {
				...settings,
				arrows: false,
			};

			const carousel = y(scope).fetch("lead-carousel");

			const condition = y(this).hasClass("mobile-carousel")
				? settingsMobile
				: settings;

			carousel.on("init", function () {
				const slide = carousel.fetch("lead-carousel-slide");
				slide.removeClass("lead-carousel-slide");
			});

			carousel.slick(condition);

			carousel.on("beforeChange", function (slick, currSlide) {
				const slide = y(currSlide.$slides.get(currSlide.currentSlide));
				const { url, title } = slide.find("figure").data();
				// Parent Carousel Data
				const { collection, collectionUrl } = carousel.data();
			});
		};
	});
