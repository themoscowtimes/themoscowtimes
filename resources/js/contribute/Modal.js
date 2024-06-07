define("contribute.Modal")
	.use("Cookie")
	.as(function (y, Cookie) {
		this.start = function (scope) {
			setTimeout(() => {
				if (Cookie.get("contribute-modal") != "suspended") {
					y(scope)
						.css("display", "flex")
						.hide()
						.fadeIn("slow")
						.parent()
						.css("overflow", "hidden");

					scope.fetch("close").on("click", function (e) {
						y(scope).fadeOut("slow");
						Cookie.set("contribute-modal", "suspended", 1);
						y(scope).parent().css("overflow", "scroll");
					});
					// Close on ESC
					y("body").on("keyup", function (e) {
						if (e.keyCode === 27) {
							y(scope).fadeOut("slow");
							Cookie.set("contribute-modal", "suspended", 1);
							y(scope).parent().css("overflow", "scroll");
						}
					});
					// On proceed button click
					y(scope)
						.fetch("contribute-btn")
						.on("click", function () {
							y(scope).fadeOut("slow");
							Cookie.set("contribute-modal", "suspended", 3);
							y(scope).parent().css("overflow", "scroll");
						});
				} else {
					y(scope).hide();
				}
			}, 10000);
		};
	});
