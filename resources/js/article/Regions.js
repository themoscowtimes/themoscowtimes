// https://stackoverflow.com/questions/52576376/how-to-zoom-in-on-a-complex-svg-structure
define("article.Regions").as(function (y) {
	this.start = function (scope) {
		const oblast = scope.fetch("oblast");
		const map = scope.fetch("map");
		const paths = document.querySelectorAll("#map path");
		const scrollItem = scope.fetch("scroll");

		let h = scrollItem[0].offsetHeight;
		map[0].setAttribute("transform", `scale(0.9${h})`);

		paths.forEach(function (item, idx) {
			item.addEventListener("mouseover", function () {
				const id = item.getAttribute("title");
				oblast.html(`${idx}. ${id}`);
			});
			item.addEventListener(
				"click",
				function (e) {
					const id = item.getAttribute("id");
					window.open("/tag/" + id);
				},
				false
			);
		});
		addEventListener("resize", function (e) {
			let h = scrollItem[0].offsetHeight;
			map[0].setAttribute("transform", `scale(0.9${h})`);
		});
	};
});
