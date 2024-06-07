define("Main").as(function (y) {
	this.start = function (scope) {
		console.log(
			"%cThe Moscow Times. Independent News from Russia.",
			"color: #3263c0; font-size: 10px;"
		);

		if (typeof window.freestar === 'object') {
			setTimeout(function () {
				window.freestar.queue.push(function () {
					window.freestar.newPushdown("themoscowtimes.com_pushdown");
				});
			}, 2000);
		}

	};
});
