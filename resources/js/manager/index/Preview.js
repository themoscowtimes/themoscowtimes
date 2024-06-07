define('manager.index.Preview')
.use('manager.Post')
.as(function(y, Post) {
	this.start = function(scope) 
	{
		scope.click(function(){
			var contents =  btoa(encodeURIComponent(JSON.stringify(scope.data('data'))).replace(/%([0-9A-F]{2})/g,
				function (match, p1) {
					return String.fromCharCode('0x' + p1);
				}
			));
			Post.make(scope.data('url')).submit({values: contents}, 'preview');
		})
	}
});
