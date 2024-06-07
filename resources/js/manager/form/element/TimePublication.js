define('manager.form.element.TimePublication')
.as(function(y){
	
	var _scope;
	var _value;
	
	this.start = function(scope)
	{
		
		_value = scope.data('value');
		_scope = scope;

		scope.fetch('toggle').change(function(){
			if(! y(this).is(':checked')) {
				scope.fetch('time').show();
			} else {
				scope.fetch('hide').show();
			}
		})

	}
	
	
	this.value = function()
	{
		if(_scope.fetch('toggle').is(':checked')) {
			return null;
		} else {
			return _scope.fetch('element-time').invoke('value');
		}
	}
});