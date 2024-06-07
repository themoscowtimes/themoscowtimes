define('article.StickyBanner')
	.as(function(y) {
		this.start = function(scope) {
			y(scope).fetch('close').on('click', function (e) {
				e.preventDefault();
				y(scope).hide();
			});
		};
	});
