define('newsletter.Banner')
.as(function(y){
	this.start = function(scope){
		var active = true;
		scope.fetch('submit').click(e => {
			if(! active) {
				return;
			}
			active = false;
			y.ajax(scope.data('url'), {
				type: 'POST',
				data: {
					email: scope.fetch('email').val(),
					name: scope.fetch('name').val(),
					tags: {
						[scope.data('newsletter')]: 1
					},
				},
				dataType: "json",
			})
			.done(function(data) {
				if (data.success) {
					scope.fetch('error').hide();
					scope.fetch('email').hide();
					scope.fetch('name').hide();
					scope.fetch('submit').hide();
					scope.fetch('done').show();
				} else {
					scope.fetch('error').text(data.message).show();
				}
			})
			.always(function() {
				active = true;
			});
		})
	}
})