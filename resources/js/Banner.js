define("Banner")
	.use("yellow.Arr")
	.as(function(y, Arr) {
		this.start = function(scope) {
			// all the available banners for this slot
			var pool = scope.data("pool");

			// get the viewports for this slot
			var viewports = scope.data("viewports");

			// get the current viewport
			var viewport = "none";
			y.document.fetch("viewport").each(function() {
				if (y(this).is(":visible")) {
					viewport = y(this).data("viewport");
					return false;
				}
			});

			if (!Arr.has(viewport, viewports)) {
				// no valid viewports: remove entire banner
				scope.fetch("banner", "closest").hide();
				return;
			}

			if (pool.length > 0) {
				pool = shuffle(pool);
				var banner = pool[0];
				if (banner.type === "tag") {
					const bannerHtml = y("<div>" + banner.html + "</div>");
					scope.append(bannerHtml);
					// Load dynamic ads through lazy load
					bannerHtml.start();
				} else if (banner.src) {
					scope.append(
						y(
							'<a href="' +
							banner.href +
							'" target="_blank"><img src="' +
							banner.src +
							'" /></a>'
						)
					);
				}
			}
		};

		var shuffle = function(a) {
			for (var i = a.length - 1; i > 0; i--) {
				var j = Math.floor(Math.random() * (i + 1));
				[a[i], a[j]] = [a[j], a[i]];
			}
			return a;
		};
	});