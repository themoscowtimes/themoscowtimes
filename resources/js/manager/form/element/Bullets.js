define('manager.form.element.Bullets')
	.use('yellow.View')
	.as(function(y, View) {
		// Set global scope
		var _scope;
		// Set global click counter for add show/hide
		var clickCounter = 0;

		this.start = function(scope) {
			_scope = scope;

			var value = scope.data('value');

			if (!y.isArray(value)) {
				value = [];
			}

			for (var i = 0; i < value.length; i++) {
				add(value[i]);
			}

			// Set counter to existing values
			clickCounter = value.length;

			clickCounter >= 3 && scope.fetch('add').hide();

			// add empty element
			scope.fetch('add').click(function() {
				clickCounter++;
				clickCounter >= 3 && scope.fetch('add').hide();
				add('');
			});
		}


		var add = function(text) {
			var element = View.make(_scope.template('bullet')).element({});
			element.fetch('text').val(text);
			_scope.fetch('container').append(element);
			element.fetch('delete').click(function() {
				clickCounter--;
				clickCounter < 3 && _scope.fetch('add').show();
				element.remove();
			});
			_scope.fetch('container').sortable()
		}


		this.value = function() {
			var value = [];
			_scope.fetch('bullet').each(function(i) {
				var text = y(this).fetch('text').val();
				if (text) {
					value.push(text);
				}
			});
			return value;
		}
	});