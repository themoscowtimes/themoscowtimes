define('account.Dashboard')
.use('yellow.View')
.use('Loading')
.use('Dialog')
.use('Cookie')
.as(function(y, View, Loading, Dialog, Cookie)
{
	
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;

		// Signout button(s)
		scope.fetch('signout').click(signout);
		
		// Sidemenu
		scope.fetch('sidemenu-expand').click(function(){
			scope.fetch('sidemenu-overlay').toggle()
			scope.fetch('sidemenu').toggle();
		})
		scope.fetch('sidemenu-overlay').click(function(){
			scope.fetch('sidemenu-overlay').hide()
			scope.fetch('sidemenu').hide();
		})
		scope.fetch('sidemenu-close').click(function(){
			scope.fetch('sidemenu-overlay').hide()
			scope.fetch('sidemenu').hide();
		})

		
		// Get account data
		Loading.show();
		y.ajax(scope.data('account'), {
			dataType: 'json'
		}).done(function(data){
			// Build top menu
			menu(data);
			// Make sidemenu clickable
			scope.fetch('sidemenu-section').click(function(){
				scope.fetch('sidemenu-link').removeClass('sidemenu__link--active');
				y(this).fetch('sidemenu-link').addClass('sidemenu__link--active');
				switch(y(this).data('section')) {
					case 'account':
						account(data);
						break;
					case 'donations':
						Loading.show();
						y.ajax(scope.data('donations'), {
							dataType: 'json'
						}).done(function(donationsData){
							donations(donationsData);
						}).fail(function(data){
							document.location.reload()
						}).always(function(){
							Loading.hide();
						})
						break;
				}
			})
			// Build account section
			account(data);
		}).always(function(){
			Loading.hide();
		})
	}
	
	
	/*
	 * Create header menu
	 */
	var menu = function(data)
	{
		// Create top menu
		var menu = View.make(_scope.template('menu'))
		.element({
			name: data.name || ''
		}, {
			signout: signout
		});

		menu.fetch('expand').click(function(e){
			e.stopPropagation();
			e.preventDefault();
			menu.fetch('options').toggle();
		})
		y(document).click(function(){
			menu.fetch('options').hide();
		})
		_scope.fetch('header').append(menu);

	}
	
	
	/*
	 * Create the account sections
	 */
	var account = function(data)
	{
		var content = View.make(_scope.template('account'), {}, {
			format: function(date) {
				var d = new Date(date);
				if(date && ! isNaN(d.getMonth())) {
					return d.toLocaleDateString('en-GB');
				} else {
					return '';
				}
			}
		})
		.element(data, {
			password: function(){
				content.fetch('password').hide();
				content.fetch('password-update').show();
			},
			passwordUpdate: function(){
				content.fetch('password-update').fetch('error').hide();
				update({
					password: content.fetch('password-update').fetch('value').val()
				}, function(message){
					Dialog.alert(message);
					account(data);
				}, function(error){
					content.fetch('password-update').fetch('error').text(error).show();
				})
			},
			passwordCancel: function(){
				content.fetch('password').show();
				content.fetch('password-update').fetch('error').hide();
				content.fetch('password-update').fetch('value').val('');
				content.fetch('password-update').hide();
			},
			information: function(){
				content.fetch('information').hide();
				content.fetch('information-update').fetch('input').each(function(){
					var name = y(this).data('name');
					if(data[name]) {
						 y(this).val(data[name]);
					}
				})
				content.fetch('information-update').show();
			},
			informationUpdate: function(){
				var values = {};
				content.fetch('information-update').fetch('input').each(function(){
					var val = y(this).val()
					if(val) {
						values[y(this).data('name')] = val;
					}
				})
				update(values, function(message){
					Dialog.alert(message);
					for(var key in values) {
						data[key] = values[key]
					}
					account(data);
				}, function(error){
					content.fetch('information-update').fetch('error').text(error).show();
				})
			},
			informationCancel: function(){
				content.fetch('information').show();
				content.fetch('information-update').fetch('error').hide();
				content.fetch('information-update').fetch('input').val('');
				content.fetch('information-update').hide();
			},
			/*
			email: function(){
				content.fetch('email').hide();
				content.fetch('email-update').show();
				
			},
			emailUpdate: function(){
				update({
					email: content.fetch('email-update').fetch('value').val()
				}, function(message){
					Dialog.alert(message);
					data.email = content.fetch('email-update').fetch('value').val();
					account(data);
				}, function(error){
					content.fetch('email-update').fetch('error').text(error).show();
				})
			},
			emailCancel: function(){
				content.fetch('email').show();
				content.fetch('email-update').fetch('error').hide();
				content.fetch('email-update').fetch('value').val('');
				content.fetch('email-update').hide();
			},
			*/
			signoff: function() {
				Dialog.alert('We hate to see you go! <br />Send an e-mail to development@themoscowtimes.com, to remove your account')
			}
		});
		
		_scope.fetch('content')
		.empty()
		.append(content);
	}
	
	
	var donations = function(data)
	{
		// Create panel
		var content = View.make(_scope.template('donations'), {}, {
			format: function(date) {
				var d = new Date(date);
				if(date && ! isNaN(d.getMonth())) {
					return d.toLocaleDateString('en-GB');
				} else {
					return '';
				}
			}
		}).element({donations: data},{
			donate: function() {
				document.location.href = _scope.data('donate');
			},
			donationCancel: function(){
				var url = y(this).data('url');
				var donation = y(this).fetch('donation', 'closest');
				Dialog.confirm('Confirm cancellation', 'Are you sure you want to stop your recurring donation to The Moscow Times', function(){
					Loading.show();
					y.ajax(url, {
						dataType: 'JSON',
						type: 'POST',
						data: {
							csrf: _scope.data('csrf')
						}
					}).done(function(response){
						y.ajax(_scope.data('donations'), {
							dataType: 'json'
						}).done(function(donationsData){
							donations(donationsData);
						}).fail(function(){
							document.location.reload();
						}).always(function(){
							Dialog.alert('Your recurring donation was cancelled');
							Loading.hide();
						})
					}).fail(function(){
						Loading.hide();
						Dialog.alert('Unable to cancel your donation. Please try again at a later time.');
					})
				})
			},
			donationUpdate: function(){
				var donation = y(this).fetch('donation', 'closest');
				donation.fetch('donation-info').hide();
				donation.fetch('donation-update').show();
			},
			donationUpdateCancel: function(){
				var donation = y(this).fetch('donation', 'closest');
				donation.fetch('donation-info').show();
				donation.fetch('donation-update').hide();
			},
			donationUpdateUpdate: function(){
				var donation = y(this).fetch('donation', 'closest');
				var url = y(this).data('url');
				var amount = String(donation.fetch('value').val());
				amount = amount.replace(/[^0-9\,\.]/i, '');
				if(amount.length == 0) {
					return;
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
				} else if(parts.length > 0) {
					amount = first + '.' + parts.shift().substr(0, 2);
				} else if(last) {
					amount = first + '.' + last.substr(0, 2);
				} else {
					amount = first;
				}

				Dialog.confirm('Confirm new donation amount', 'Are you sure you want to change your recurring donation amount to $' + amount + ' ?', function(){
					Loading.show();
					y.ajax(url, {
						dataType: 'JSON',
						type: 'POST',
						data: {
							csrf: _scope.data('csrf'),
							amount: amount
						}
					}).done(function(response){
						if(response.success) {
							y.ajax(_scope.data('donations'), {
								dataType: 'json'
							}).done(function(donationsData){
								donations(donationsData);
							}).fail(function(){
								document.location.reload();
							}).always(function(){
								Dialog.alert('Your recurring donation was updated');
								Loading.hide();
							})
						} else {
							Loading.hide();
							Dialog.alert(response.message);
						}
					}).fail(function(){
						Loading.hide();
						Dialog.alert('Unable to update your donation. Please try again at a later time.');
					})
				})
			},
		});
		
		// Add to screen
		_scope.fetch('content')
		.empty()
		.append(content);
	}
	
	
	/**
	 * Update account data
	 */
	var update = function(data, success, fail)
	{
		data.csrf = _scope.data('csrf');
		Loading.show();
		y.ajax(_scope.data('update'), {
			dataType: 'JSON',
			type: 'POST',
			data: data
		}).done(function(data){
			if(data.success) {
				success(data.message);
			} else {
				fail(data.message);
			}
		}).fail(function(){
			fail('Unable to update your information. Please try again at a later time.');
		}).always(function(){
			Loading.hide();
		});
	}
	
	
	/*
	 * Sign out and redirect to signin
	 */
	var signout = function()
	{
		Cookie.delete('contribute-modal');
		Loading.show();
		y.ajax(_scope.data('signout'), {
			dataType: 'JSON',
		}).done(function(data){
			if(data.success) {
				window.location.href = _scope.data('signin');
			}
		}).always(function(){
			Loading.hide();
		});
	}
});