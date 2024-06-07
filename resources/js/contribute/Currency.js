define("contribute.Currency")	
.as(function (y) {
	this.start = function (scope) {
		const zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		if(zone.indexOf('Europe/') === 0) {
			scope.text('€')
		} else {
			scope.text('$')
		}
	}
});



		