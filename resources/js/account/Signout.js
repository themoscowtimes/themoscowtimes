define('account.Signout')
.use('Loading')
.as(function(y, Loading)
{
	this.start = function(scope)
	{
		scope.click(function(){
			Loading.show();
			y.ajax(_scope.data('signout'), {
				dataType: 'JSON',
			}).done(function(data){
				if(data.success) {
					window.location.href = _scope.data('done');
				}
			}).always(function(){
				Loading.hide();
			});
		})
	}
});