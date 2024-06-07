define('contribute.Form')
.as(function(y){
	
	var _scope;
	var _period;

	
	this.start = function(scope)
	{
		_scope = scope;
		
		var firstname = scope.fetch('input-firstname');
		var lastname = scope.fetch('input-lastname');
		
		scope.fetch('amount').each(function(){
			var amount = y(this);
			amount.fetch('option').click(function(){
				amount.fetch('option').removeClass('active');
				y(this).addClass('active');
				amount.data('option', y(this).data('amount'));
				if(y(this).data('amount') == 'other') {
					amount.fetch('other').show();
				} else {
					amount.fetch('other').hide();
				}
				label('$');
			})
		})


		
		scope.fetch('period').click(function(){
			scope.fetch('error').remove();
			scope.fetch('period').removeClass('active');
			y(this).addClass('active');
			_period = y(this);
			
			var period = y(this).data('period');
			
			scope.fetch('amount').hide();
			scope.fetch('amount_' + period).show();
			
			if(period == 'once') {
				firstname.fetch('input-wrapper', 'closest').hide();
        lastname.fetch('input-wrapper', 'closest').hide();
			} else {
				firstname.fetch('input-wrapper', 'closest').show();
        lastname.fetch('input-wrapper', 'closest').show();
			}
			label('$');
		});
		
		
		// inital state
		scope.fetch('period_monthly').click();
		scope.fetch('amount_once').fetch('option_50').click();
		scope.fetch('amount_monthly').fetch('option_10').click();
		scope.fetch('amount_annual').fetch('option_50').click();
		
		scope.fetch('other').on('keyup keydown change',function(){
			var val = y(this).val();
			val = val.replace(/[^0-9\,\.]/i, '');
			y(this).val(val);
			label('$');
		});

		scope.fetch('submit').click(function(){
			y.ajax(scope.data('url'), {
				dataType: 'JSON',
				method: 'POST',
				data: {
					amount: getAmount(),
					period: getPeriod(),
					firstname: scope.fetch('input-firstname').val(),
					lastname: scope.fetch('input-lastname').val(),
					email: scope.fetch('input-email').val(),
				}
			}).done(function(data){
				if(data.success && data.url) {
					document.location.href = data.url;
				} else {
					scope.fetch('error').remove();
					for(var key in data.errors) {
						if(key == 'amount') {
							_scope.fetch('amount_' + getPeriod()).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
						} else {
							scope.fetch('input-' + key).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
						}
					}
				}
			});
		});
	}
	
	var label = function(currency)
	{
		var amount = currency + getAmount();
		var period = getPeriod();
		var periods = {
			once: '',
			monthly: 'each month',
			annual: 'each year',
		};
		var analyticsKeys = {
			once: 'once',
			monthly: 'monthly',
			annual: 'annually'
		};

		_scope.fetch('submit').text('Contribute ' + amount + ' ' + periods[period]);
	}
	
	var getAmount = function()
	{
		var period = getPeriod();
		var amount = _scope.fetch('amount_' + period).data('option');
		if(amount == 'other') {
			amount = _scope.fetch('amount_' + period).fetch('other').val();
		} 
		amount = String(amount);
		amount = amount.replace(/[^0-9\,\.]/i, '');
		if(amount.length == 0) {
			return '';
		}
		
		var parts = amount.split(/[\,\.]+/);
		

		var first = parts.shift();
		var last = parts.pop();
		var thousands = first.length <= 3;
		for( var i = 0; i < parts.length; i++) {
			var part = parts[i];
			if(part.length !== 3) {
				thousands = false;
				break;
			}
		}

		if(thousands) {
			amount = first + parts.join('') ;
			if(last && last.length === 3) {
				amount = amount + last;
			} else if(last && last.length > 0) {
				amount = amount + '.' + last.substr(0, 2);
			}
		} else {
			var num = parts.shift();
			if(num) {
				amount = first + '.' + num;
			} else {
				amount = first;
			}
		}
		return amount;
	}
	
	
	var getPeriod = function()
	{
		return _period.data('period');
	}
});