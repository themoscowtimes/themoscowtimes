define('Dialog')
.use('yellow.View')
.use('Overlay')

// templates
.template('dialog' ,
'<div class="dialog">' +
	'<div class="dialog__content">' +
		'<span class="icon dialog__close clickable" href="#" y-name="close">&times;</span>' +
		'<h3 class="dialog__title">{{title}}</h3>' +
		'<div class="dialog__body">' +
			'<p>{{{body}}}</p>' +
		'</div>' +
		'<div class="dialog__buttons" y-name="buttons" ></div>' +
	'</div>' +
'</div>')

.template('iframe' ,
'<div class="dialog">' +
	'<span class="icon dialog__close clickable" href="#" y-name="close">&times;</span>' +
	'<iframe width="100%" height="100%" name="{{name}}" y-name="iframe" frameborder="0"></iframe>' +
'</div>')

.template('button' , '<a href="#" target="{{ target}}" class="dialog__button button button--{{type}} mr-1" role="button">{{label}}</a>')



// static functions
.set({
	// set lang
	lang: {
		ok: 'Ok',
		cancel: 'Cancel'
	},
	// preset dialog for alerting
	alert: function(message, callback){
		var y = this.y;
		var dialog = this.make({
			close: callback ? callback : true,
			title: '',
			body: message,
			buttons: [
				{type: 'primary', label: this.lang['ok'], action: function(){
					dialog.remove()
					if(y.isFunction(callback)) {
						callback();
					}
				}}
			],
			width: 'auto',
			height: 300,
		});
		return dialog;
	},
	// preset dialog for confirm
	confirm: function(title, message, callback, cancelCallback){
		var y = this.y;
		var dialog = this.make({
			title: title,
			body: message,
			close: false,
			buttons: [
				{type: 'primary', label: this.lang['ok'], action: function(){
					dialog.remove(); 
					if(y.isFunction(callback)) {
						callback();
					}
				}},
				{type: 'default', label: this.lang['cancel'], action: function(){
					dialog.remove();
					if(y.isFunction(cancelCallback)) {
						cancelCallback(); 
					}
				}}
			],
			maxWidth: 600,
			height: 300,
		});
		return dialog;
	},
	iframe: function(src, data){
		var dialog = this.make({
			template: 'iframe',
			src: src,
		});
		return dialog;
	},
})


.as(function(y, View, Overlay, template)
{
	// this helper
	var _this = this;
	
	// overlay instance
	var _overlay;
	
	var _config = {
		template: 'dialog',
		name: '_' + new Date().getTime(),
		width: 'auto',
		maxWidth: 1000,
		height: 'auto',
		maxHeight: 800,
		title: '',
		body: '',
		src: '',
		data: null,
		buttons: [],
		close: true,
		show: true
	}
	
	this.start = function(config)
	{
		// merge config
		for(var option in config){
			_config[option] = config[option]
		}
		
		// create dialog
		var dialog = View.make(template(_config.template)).element(_config);
		
		// close button
		if(_config.close){
			dialog.fetch('close').click(function(e){
				e.preventDefault();
				if(y.isFunction(_config.close)){
					_config.close();
				}
				_this.remove();
				
			})
		} else {
			dialog.fetch('close').hide();
		}
		

		// additional buttons
		var buttons = dialog.fetch('buttons');
		for(var i = 0; i < _config.buttons.length; i++){
			// create button element
			var button = View.make(template('button')).element({
				type: _config.buttons[i].type,
				label: _config.buttons[i].label,
			});
			
			// add action
			var action = (function(a){ return a})(_config.buttons[i].action);
			if(y.isString(action)){
				// add action
				button.attr('href', action);
				if(y.isString(_config.buttons[i].target)){
					button.attr('target', _config.buttons[i].target);
				}
			} else if(y.isFunction(action)){
				// closure in loop fix 
				button.click((function(a){
					return function(e){
						e.preventDefault();
						a(y(this));
					}
				})(action));
			}
			buttons.append(button);
		}
		
		// create overlay with dialog
		_overlay = y.outer().get('Overlay').make(dialog, _config);
		 if (_config.template == 'iframe') {
			// just set iframe
			dialog.fetch('iframe').attr('src', _config.src);
		}
	}
	

	this.remove = function()
	{
		// remove the overlay
		_overlay.remove();
		// delete this instance
		delete this;
	}
});