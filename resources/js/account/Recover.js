define('account.Recover')
.use('Loading')
.as(function(y, Loading)
{
	
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;
		scope.fetch('submit').click(recover);
		
		scope.fetch('email').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				recover();
			}
		});
		scope.fetch('signin').click(function(){
			window.location.href = scope.data('signin');
		});
	}
	

	var recover = function()
	{
		_scope.fetch('error').hide();
		
		var email = _scope.fetch('email').val();

		Loading.show();

		y.ajax(_scope.data('recover'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				email: email,
			}
		}).done(function(data){
			if(data.success) {
				_scope.fetch('done').show();
				_scope.fetch('form').hide();
			} else {
				_scope.fetch('error').text(data.message).show();
			}
		}).always(function(){
			Loading.hide();
		});
	}
});