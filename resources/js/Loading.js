define('Loading')
.use('yellow.View')
.use('Overlay')
.template('loading' , '<div y-name="__loading__" class="loading" style="' +
	'width:100%; ' +
	'height: 100%;' +
	'background-image: url(\'data:image/gif;base64,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\');' +
	'background-repeat: no-repeat;' +
	'background-position: center center;' +
'"></div>')

.set({
	_instance: null,
	instance: function() 
	{
		if(this._instance === null) {
			this._instance = this.make()
		}
		return this._instance;
	},
	
	show: function(){
		this.instance().show();
	},
	
	hide: function(){
		this.instance().hide();
	},
})

.as(function(y, View, Overlay, template)
{
	// overlay instance
	var _overlay;
	
	this.start = function()
	{
		// check if there is a loading element in the outer window
		var loading = y.outer().window.fetch('__loading__');
		
		if(loading.length > 0) {
			// if there is one, the overlay is in the element data
			_overlay = loading.data('overlay');
		} else {
			// if there isnt one, create loader element
			var loading = View.make(template('loading')).element();
		
			// create overlay with loader element, but do this in the outer overlay
			_overlay = y.outer().get('Overlay').make(loading, {
				close: false,
				width: 50, 
				height: 50,
				show: false
			});
			// set the overlay in the element data
			loading.data('overlay', _overlay);
		}
	}
	
	this.show = function() {
		_overlay.show(200);
	}
	
	this.hide = function() {
		_overlay.hide(200);
	}
});