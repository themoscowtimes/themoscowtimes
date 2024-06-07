define('account.Register')
.use('Loading')
.as(function(y, Loading, FB)
{
	var _scope;
	var _email;
	
	this.start = function(scope)
	{
		_scope = scope;
		
		_email = localStorage.getItem('email');
		localStorage.removeItem('email');
		if(_email) {
			scope.fetch('email').val(_email);
		}
		
		scope.fetch('submit').click(register);
		
		scope.fetch('email').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				register();
			}
		});
		
		scope.fetch('password').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				register();
			}
		});
		
		scope.fetch('signin').click(function(){
			window.location.href = scope.data('signin');
		});
	}
	
	
	var register = function()
	{
		_scope.fetch('error').hide();
		
		var email = _scope.fetch('email').val();
		var password = _scope.fetch('password').val();
		var agreed = _scope.fetch('agreed').is(':checked') ? '1' : '0';
		
		if(email && password) {
			Loading.show();
			
			var data = {
				email: email,
				agreed: agreed,
			}
			
			data.password = password;
			
			y.ajax(_scope.data('register'), {
				type: 'POST',
				dataType: 'JSON',
				data: data
			}).done(function(data){
				if(data.success) {
					window.location.href = _scope.data('done');
				} else {
					_scope.fetch('error').text(data.message).show();
				}
			}).always(function(){
				Loading.hide();
			});
		}
	}
});