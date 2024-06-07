define('contribute.Donate')
	.as(function(y) {

		var _scope;
		var selectedAmount;

		this.start = function(scope) {
			_scope = scope;

			// click amounts
			scope.fetch('amount').click(function() {
				// set label
				scope.fetch('amount').removeClass('active');
				y(this).addClass('active');

				// set current amount
				selectedAmount = y(this).data('amount');

				// show / hide other amount
				if (y(this).data('amount') === 'other') {
					scope.fetch('other').show();
				} else {
					scope.fetch('other').hide();
				}

				// put label on button
				label(scope.attr('data-currency') === 'usd' ? '$' : '€');
			});

			// initial amount
			scope.fetch('amount_' + scope.data('amount')).click();

			// manual amount
			scope.fetch('other').on('keyup keydown change', function() {
				var val = y(this).val();
				val = val.replace(/[^0-9\,\.]/i, '');
				y(this).val(val);
				// put label on button
				label(scope.attr('data-currency') === 'usd' ? '$' : '€');
			});

			// Submit form
			scope.fetch('submit').click(function() {
				y.ajax(scope.data('url'), {
					dataType: 'JSON',
					method: 'POST',
					data: {
						amount: getAmount(),
						period: scope.data('period'),
						firstname: scope.fetch('firstname').val(),
						lastname: scope.fetch('lastname').val(),
						country: scope.fetch('country').val(),
						phone: scope.fetch('phone').val(),
						agree: scope.fetch('agree').is(':checked') ? 1 : 0,
						email: scope.fetch('email').val(),
						currency: scope.attr('data-currency')
					}
				}).done(function(data) {
					if (data.success && data.url) {
						document.location.href = data.url;
					} else {
						scope.fetch('error').remove();
						for (var key in data.errors) {
							if (key === 'amount') {
								_scope.fetch('other').after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
							} else if (key === 'agree') {
								scope.fetch(key).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
							} else {
								scope.fetch(key).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
							}
						}
					}
				});
			});
		}


		var label = function(currency) {
			var amount = `<span y-name="submit-currency-label">${currency}</span>` + getAmount();
			var periods = {
				once: '',
				monthly: 'each month',
				annual: 'each year',
			};
			_scope.fetch('submit').html('Contribute ' + amount + ' ' + periods[_scope.data('period')]);
		}


		var getAmount = function() {

			var amount = selectedAmount;
			if (amount === 'other') {
				amount = _scope.fetch('other').val();
			}

			amount = String(amount);
			amount = amount.replace(/[^0-9\,\.]/i, '');
			if (amount.length === 0) {
				return '';
			}

			var parts = amount.split(/[\,\.]+/);

			var first = parts.shift();
			var last = parts.pop();
			var thousands = first.length <= 3;
			for (var i = 0; i < parts.length; i++) {
				var part = parts[i];
				if (part.length !== 3) {
					thousands = false;
					break;
				}
			}

			if (thousands) {
				amount = first + parts.join('');
				if (last && last.length === 3) {
					amount = amount + last;
				} else if (last && last.length > 0) {
					amount = amount + '.' + last.substr(0, 2);
				}
			} else if (parts.length > 0) {
				amount = first + '.' + parts.shift().substr(0, 2);
			} else if (last) {
				amount = first + '.' + last.substr(0, 2);
			} else {
				amount = first;
			}
			return amount;
		}
	});