define('Overlay')

.use('yellow.View')

.template('overlay','<div class="overlay" style="position:fixed; top:0; left: 0; z-index:1000; background:rgba(0,0,0,0.2); width:100%; height: 100%;">' +
	'<div class="overlay-background" y-name="background" style="position: fixed;"></div>' +
	'<div class="overlay-container" y-name="container" style="position: absolute; "></div>' +
'</div>')


.as(function(y, View, template)
{
	// this alias
	var _this = this;
	
	// options
	var _config = {
		width: 'auto',
		maxWidth: 800,
		height: 'auto',
		maxHeight: 600,
		close: true,
		show: false,
	}
	
	// overlay element
	var _overlay;


	this.start = function(content, config){
		// merge options
		for(var option in config){
			_config[option] = config[option]
		}
		// create overlay
		_overlay = View.make(template('overlay')).element();
		
		// set dimensions and position
		var container = initContainer();

		// add the supplied content
		container.append(content);
		
		// add close click to background
		if(_config.close){
			_overlay.fetch('background').click(function(){
				if(y.isFunction(_config.close)){
					_config.close();
				}
				_this.remove();
			});
		}
		// add it to the body
		y.outer()('body').append(_overlay);
		
	
		// hide it
		if(_config.show === false){
			_overlay.hide();
		} 
	}
	
	
	/*
	 * Resize and position container
	 */
	var initContainer = function()
	{
		var container = _overlay.fetch('container');
		var windowWidth = y.outer().window.width();
		var windowHeight = y.outer().window.height();
		
		var width = _config.width === 'auto' ? windowWidth * 0.9 : _config.width;
		var height = _config.height === 'auto' ? windowHeight * 0.9 : _config.height;
		
		width = _config.maxWidth && width > _config.maxWidth ? _config.maxWidth : width;
		height = _config.maxHeight && height > _config.maxHeight ? _config.maxHeight : height;
		
		container.width(width);
		container.height(height);
		
		container.css('top', Math.round((windowHeight - height) / 2));
		container.css('left', Math.round((windowWidth - width) / 2));


		return container;
	}
	
	
	/**
	 * Show the overlay
	 */
	this.show = function(time)
	{
		if(time){
			_overlay.fadeIn(time);
		} else {
			_overlay.show();
		}
	}
	
	
	/**
	 * Hide the overlay
	 */
	this.hide = function(time, callback)
	{
		if(time){
			_overlay.fadeOut(time, callback);
		} else {
			_overlay.hide();
		}
	}
	

	/**
	 * Show the overlay
	 */
	this.remove = function(time)
	{
		if(time){
			this.hide(time,function(){
				_overlay.remove();
				delete _this;
			});
		} else {
			_overlay.remove();
			delete _this;
		}
	}
});