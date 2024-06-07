define('manager.form.Campaign')
.as(function(y) {

	this.start = function(scope) 
	{
		
		var interval = setInterval(function(){
			var parts = window.location.href.split('/');
			var last = parts.pop();
			if( new Number(last) == last) {
				y.log('ok');
				scope.fetch('advertorial').attr('href', scope.fetch('advertorial').attr('href').replace('{{id}}', last));
				scope.fetch('advertorials').attr('href', scope.fetch('advertorials').attr('href').replace('{{id}}', last));
				//scope.fetch('banner').attr('href', scope.fetch('banner').attr('href').replace('{{id}}', last));
				//scope.fetch('banners').attr('href', scope.fetch('banners').attr('href').replace('{{id}}', last));
				scope.fetch('create').hide();
				scope.fetch('update').show();
				clearInterval(interval);
			} else {
				scope.fetch('create').show();
				scope.fetch('update').hide();
			}
		}, 100);
	}
});