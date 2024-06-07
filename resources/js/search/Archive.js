define('search.Archive')


//.use('Calendar', 'flatpickr')

.as(function(y) 
{
	
	var _scope;

	
	this.start = function(scope) 
	{
		_scope = scope;
		
		scope.fetch('submit').click(function(e){
			go()
		})
		
		scope.fetch('query').keyup(function(e) {
			if (e.which === 13) {
				go()
			}
		});
		
		var calendar = flatpickr(scope.fetch('from')[0], {
			altInput: true,
			altInputClass: 'input-flatpickr',
			altFormat: 'j F, Y',
			dateFormat: 'Y-m-d',
			locale: 'en'
		});
		
		var calendar = flatpickr(scope.fetch('to')[0], {
			altInput: true,
			altInputClass: 'input-flatpickr',
			altFormat: 'j F, Y',
			dateFormat: 'Y-m-d',
			locale: 'en'
		});
	}
		

	function go()
	{
		var query = _scope.fetch('query').val()
		if(query) {
			var url = _scope.data('url') + '?q=' + encodeURIComponent(query)
			var from = _scope.fetch('from').val()
			var to = _scope.fetch('to').val()
			if(from) {
				url = url + '&from=' + from
			}
			if(to) {
				url = url  + '&to=' + to
			}
			document.location.href = url;
		}
	}
});
