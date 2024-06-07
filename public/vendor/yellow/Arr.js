define('yellow.Arr')
.as({
	has: function(value, arr, strict)
	{
		for(var i = 0; i < arr.length; i++){
			if(strict){
				if(value === arr[i]){
					return true;
				}
			} else {
				if(value == arr[i]){
					return true;
				}
			}
		}
		return false;
	},
	keys: function(obj)
	{
		var result = [];
		for(var i in obj){
			result.push(i);
		}
		return result;
	},
	values: function(obj)
	{
		var result = [];
		for(var i in obj){
			result.push(obj[i]);
		}
		return result;
	}
});