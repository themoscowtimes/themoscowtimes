define("contribute.Tabs").as(function (y) {
	function updateCurr(arr, label) {
		return arr.map(function (val) {
			y(val).text(label);
		});
	}

	this.start = function (scope) {
		// Convert array
		const arr = $.map(scope.fetch("curr-label"), function (value) {
			return [value];
		});

		updateCurr(arr, "$");

		scope.fetch("tab").click(function () {
			scope.fetch("tab").removeClass(scope.data("active"));
			y(this).addClass(scope.data("active"));
			scope.fetch("content").hide();
			scope.fetch(y(this).data("content")).show();
		});

		// Select currency and set on form data attribute
		scope.fetch("currency-selector").on("change", function (e) {
			let label = y(this).val() === "usd" ? "$" : "€";
			scope.fetch("donate-form").attr("data-currency", y(this).val());
			updateCurr(arr, label);
			$.map(scope.fetch("submit-currency-label"), function (val) {
				return y(val).html(label);
			});
		});
		
		const zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		if(zone.indexOf('Europe/') === 0) {
			scope.fetch("currency-selector").val('eur')
			scope.fetch("currency-selector").change()
		}
	};
});
