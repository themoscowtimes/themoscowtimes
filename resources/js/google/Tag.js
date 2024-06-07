define('google.Tag')
.set({
	event:  function(name, data){
		this.make().event(name, data);
	},
	formsubmit:  function(data){
		this.make().event('formsubmit', data);
	},
})
.as(function(y)
{
	this.start = function()
	{
		window.dataLayer = window.dataLayer || [];
	}
	
	this.event = function(name, data)
	{
		if(! y.isObject(data)) {
			data = {};
		}
		
		data.event = name;
		window.dataLayer.push(data);
	}
});