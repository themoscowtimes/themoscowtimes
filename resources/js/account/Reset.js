define('account.Reset')
.use('Loading')
.as(function(y, Loading)
{
	
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;
		scope.fetch('submit').click(reset);
		
		scope.fetch('email').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				reset();
			}
		});
		
		scope.fetch('signin').click(function(){
			window.location.href = scope.data('signin');
		});
	}
	

	var reset = function()
	{
		_scope.fetch('error').hide();
		
		var password = _scope.fetch('password').val();

		Loading.show();

		y.ajax(_scope.data('reset'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				password: password,
				token: _scope.data('token'),
				csrf: _scope.data('csrf'),
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