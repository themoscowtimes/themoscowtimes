define('contribute.Done')
.use('Cookie')
.as(function(y,Cookie){
	this.start = function(scope)
	{
		Cookie.set('contribute-cta', 'suspended', '31');
	}
});