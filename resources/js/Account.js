define('Account')
	.as(function(y) {
		this.start = function(scope) {
			var letter = scope.data('letter') || 'T';

			scope.fetch('account').toggle();

			scope.fetch('letter')
				.text(letter.toUpperCase())
				.click(function(e) {
					e.stopPropagation();
					scope.fetch('menu').toggle();
				});

			y('body').click(function() {
				scope.fetch('menu').hide()
			});

			scope.fetch('signin').hide();
		}
	});