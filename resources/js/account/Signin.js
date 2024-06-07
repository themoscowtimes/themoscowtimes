define('account.Signin')
.use('Loading')
.use('Cookie')
.as(function(y, Loading, Cookie)
{
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;
		
		scope.fetch('submit').click(function(e){
			e.preventDefault();
			signin();
		});
		
		scope.fetch('identity').keyup(function(e){

			if (e.which == 13) {
				e.preventDefault();
				signin();
			}
		});
	
		scope.fetch('credentials').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				signin();
			}
		});
		
		scope.fetch('recover').click(function(){
			window.location.href = scope.data('recover');
		});
		
		scope.fetch('register').click(function(){
			window.location.href = scope.data('register');
		});
	}
	

	var signin = function()
	{
		_scope.fetch('error').hide();
		
		var identity = _scope.fetch('identity').val();
		var credentials = _scope.fetch('credentials').val();
		//var permanent = _scope.fetch('permanent').is(':checked') ? '1' : '0';
	
		Loading.show();

		y.ajax(_scope.data('signin'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				identity: identity,
				credentials: credentials,
				// permanent: permanent,
			}
		}).done(function(data){
			if(data.success) {
				window.location.href = _scope.data('done');
				// Set cookie for contribute modal
				Cookie.set('contribute-modal', 'suspended', 365);
			} else {
				_scope.fetch('error').text(data.message).show();
			}
		})
		.always(function(){
			Loading.hide();
		});
	}
});