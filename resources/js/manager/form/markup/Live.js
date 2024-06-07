define('manager.form.markup.Live')
.as(function(y){
	
	this.start = function(scope)
	{
		var interval = setInterval(function(){
			var url = new URL(window.location.href);
			var part = url.pathname.split('/').slice(-1)[0];
			if (typeof part === 'string' && !isNaN(part)) {
				var btn = scope.fetch('posts');	
				btn.attr('href', btn.attr('href').replace('{{id}}', part)).show();
				scope.fetch('inactive').hide();
				clearInterval(interval);
			}
		}, 500)
	}
});