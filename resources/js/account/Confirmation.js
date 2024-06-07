define('account.Confirmation')
.use('Loading')
.as(function(y, Loading)
{
	this.start = function(scope)
	{
		scope.fetch('confirmation').click(function(){
			Loading.show();
			scope.fetch('confirmation').hide();
			scope.fetch('error').hide();
			y.ajax(scope.data('confirmation'), {
				dataType: 'JSON'
			}).done(function(data){
				if(data.success) {
					scope.fetch('sent').show();
				} else {
					scope.fetch('error').text(data.message).show();
				}
			}).always(function(){
				Loading.hide();
			})
		})
	}
});