define('manager.index.filter.Author')
.as(function(y) {
	this.start = function(scope) 
	{
		scope.fetch('input').change(function(){
			if(y(this).val() !== '') {
				scope.fetch('filter').data('load', true);
				scope.fetch('select').val(y(this).val()).change();
			}
		})
		
		scope.fetch('select').change(function(){
			if(y(this).val() == -1) {
				scope.fetch('input').val('');
			}
		})
	}
});
