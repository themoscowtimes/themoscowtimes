define('Timeago')
.as(function(y) {
	this.start = function(scope) {
		
		
		var timestamp = new Date().getTime();

		var date = new Date(
		  scope.attr('datetime').split(' ').join('T')
		).getTime();

		var day = 24 * 60 * 60 * 1000 * 1.5;
		var yesterday = timestamp - day;
		var tomorrow = timestamp + day;

		if (yesterday < date && date < tomorrow) {		
			scope.timeago();
		}
	}
});
