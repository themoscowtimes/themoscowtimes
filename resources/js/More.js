define("More").as(function(y) {
	this.start = function(scope) {
		var offset = scope.data("start") || 0;
		var step = scope.data("step") || 18;

		scope.on("click", function() {
			y.ajax(scope.data("url").replace("{{offset}}", offset), {
				dataType: "html"
			}).done(function(html) {
				if (html.trim() == "") {
					scope.hide();
				} else {
					scope.before(y(html));
				}
			});
			offset += step;
		});
	};
});
