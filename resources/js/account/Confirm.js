define('account.Confirm')
.use('Loading')
.as(function(y, Loading)
{
	this.start = function(scope)
	{
		Loading.show();
		y.ajax(scope.data('customer'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				token: scope.data('token')
			}
		}).done(function(data){
			if(y.isObject(data))
			scope.fetch('input').each(function(){
				var name = y(this).data('name');
				if(name == 'phone_country') {
					if(data.phone && data.phone.country) {
						y(this).val(data.phone.country);
					}
				} else if(name == 'phone_number') {
					if(data.phone && data.phone.number) {
						y(this).val(data.phone.number);
					}
				} else {
					if(data[name]) {
						y(this).val(data[name]);
					}
				}
			})			
		}).always(function(){
			Loading.hide();
		})
		
		

		scope.fetch('submit').click(function(){
			Loading.show();
			var data = {
				token: scope.data('token'),
				csrf: scope.data('csrf'),
			}
			
			// get filled in variables
			scope.fetch('input').each(function(){
				var val = y(this).val()
				if(val) {
					data[y(this).data('name')] = val;
				}
			})

			y.ajax(scope.data('confirm'), {
				type: 'POST',
				dataType: 'JSON',
				data: data
			}).done(function(data){
				if(data.success) {
					window.location.href = scope.data('done');
				} else {
					scope.fetch('error').text(data.message).show();
				}
			}).fail(function(){
				scope.fetch('error').text('Unable to store your information').show();
			}).always(function(){
				Loading.hide();
			});
		});
	}
});