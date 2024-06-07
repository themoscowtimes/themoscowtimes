define('manager.form.element.BannerPositions')
.as(function(y){
	
	var _scope;

	this.start = function(scope)
	{
		_scope = scope;
	}
	
	this.value = function()
	{
		var value = [];
		_scope.fetch('checkbox').each(function(){
			if(y(this).is(':checked')) {
				value.push(y(this).val());
			}
		});
		return value;
	}
});