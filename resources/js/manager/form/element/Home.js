define('manager.form.element.Home')
.use('yellow.View')
.use('manager.Post')
.as(function(y, View, Post){
	
	this.start = function(scope)
	{
		var post = Post.make(scope.data('url'));
		var state = '';
		var reload = function(){
			var values = scope.fetch('form', 'closest').invoke('values');
			var contents =  btoa(encodeURIComponent(JSON.stringify(values)).replace(/%([0-9A-F]{2})/g,
				function (match, p1) {
					return String.fromCharCode('0x' + p1);
			}));
			
			if(contents !== state) {
				post.submit({values: contents}, 'preview-inline')
			}
			
			state = contents;
		}
		
		setInterval(reload, 200);
		reload();
	}
});