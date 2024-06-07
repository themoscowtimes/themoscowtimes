

//_____ yellow/View.js _____//

/**
* View component.
* Example below
* 
* Set the templates in the html
<script type="text/html" y-name="viewname1">
	<div>

		{%
		console.log('this is literal javascript'); 
		var subtitle = 'Subtitle';
		%}

		{{ title }}<br />
		{{ subtitle }}<br />
		{{ title + subtitle }}<br />
		{{ title|helper }}<br />
		{{{ nonescaped }}}

		{% if content.summary %}
			{{ content.summary }}<br />
		{% elseif content.body %}
			{{ content.body }}<br />
		{% else %}
			No content<br />
		{% endif %}


		{% each images as image %}
			{{ image }}<br />
		{% endeach  %}

		{% each users as id : name %}
			{{ id }}: {{ name }}<br />
		{% endeach %}

		{% each cases as case %}
			{% include partialname case %}
		{% endeach %}
	
		{% include partialname product %}
	</div>
</script>
	
<script type="text/html"  y-name="viewname2">
	<div>
		{{ title }}
	</div>
</script>
	


* Render template with data

var data = {
	title : 'Title',
	content : {
		summary : 'Summary',
		body : 'Body'
	}, 
	images : ['img1','img2'],
	files : {
		1 : 'file1',
		2 : 'file2'
	},
	users : {
		12 : 'Username',
		17 : 'Username'
	},
	cases : {
		{title: 'Case title'},
		{title: 'Case title2'},
	},
	product : {title: 'Product title'}
}


*/
define('yellow.View')
.set({
	tokens: {
		open: /\{\%/.source,
		close: /\%\}/.source,
	},
	patterns: {

		condition: 
		'\\s*' + 
		/(\?\s|if\s|\?\?|elseif\s|else|\/\?|endif|\/if)/.source + 
		'\\s*' + 
		/([\s\S]*?)/.source + 
		'\\s*',
		
		section:
		'\\s*' + 
		/(\#|\~|each\s|filter\s|has\s|endeach|endfilter|endhas|\/)/.source +
		'\\s*' + 
		/([a-zA-Z0-9\.\_]+?){0,1}/.source + // minify will choke on ?/ so we replaced it with {0,1}/
		'\\s*' + 
		/(?:as\s+(\w+)){0,1}/.source +
		'\\s*' + 
		/(?:\:\s*(\w+)){0,1}/.source +
		'\\s*',

		partial:
		'\\s*' + 
		/(?:\>|include\s)/.source +
		'\\s*' + 
		/([\w]+)/.source + 
		'\\s*' + 
		/(.+?)/.source + 
		'\\s*',

		literal: 
		/([\s\S]+?)/.source,

		raw:
		/\{\{\{/.source +
		/([\s\S]+?)/.source +
		/\}\}\}/.source,
				
		output:
		/\{\{/.source +
		/([\s\S]+?)/.source +
		/\}\}/.source
	},
	
	matchers: function() {
		var regexes;
		if(! regexes) {
			regexes = {};
			for(var name in this.patterns) {
				if(this.patterns.hasOwnProperty(name)) {
					if(name === 'raw' || name === 'output') {
						var pattern = this.patterns[name];
					} else {
						var pattern = this.tokens.open + this.patterns[name] + this.tokens.close;
					}
					regexes[name] = new RegExp(pattern, 'g');
				}
			}
		}
		return regexes;
	},
	
	_helpers: {},
	
	helper: function(name, helper) {
		this._helpers[name] = helper;
	},
	
	helpers: function() {
		return this._helpers;
	}
})
.as(function(y, self)
{
	// the provided template
	var _template;
	
	// the provided partials
	var _partials = {};
	
	// the provided helpers
	var _helpers = {};
	
	// renderer
	var _renderer;
	
	// id for nested sections
	var _section = 0;
	
	/**
	 * Make a view
	 * @param string template
	 * @param object partials
	 * @param object helpers
	 * @returns function
	 */
	this.start = function(template, partials, helpers)
	{	
		// test if valid template
		if(!y.isString(template)){
			if(y.isFunction(template.html)){
				template = template.html();
			} else {
				throw new Error('Template is not a string');
			}
		}
		
		// escape \ and ", store template
		_template = escape(template); 

		//  escape \ and ", store partials
		if(y.isObject(partials)) {
			for (var name in partials) {
				if(partials.hasOwnProperty(name)) {
					_partials[name] = escape(partials[name]);
				}
			}
		}
		
		// store helpers
		if(y.isObject(helpers)) {
			_helpers = helpers;
		}
	};


	/**
	 * Add a named partial
	 */
	this.partial = function(name, partial)
	{
		_partials[name] = escape(partial);
		return this;
	};


	/**
	 * Add a named helper
	 */
	this.helper = function(name, helper)
	{
		_helpers[name] = helper;
		return this;
	};
	
	
	/**
	* Get a started element from rendered data
	*/
	this.element = function(data, handlers)
	{
		// get element
		var element = y(this.render(data)).first();
		
		// start element with given handlers
		element.start(handlers);
		
		// return as jquery element
		return element;
	};


	/**
	* Render data to string
	*/
	this.render = function(data)
	{
		// compile if not yet done
		if(! _renderer){
			_renderer = renderer(_template);
		}

		// make sure data is an object
		if(! y.isObject(data)){
			data = {};
		}

		// create helper hash from global and local helpers
		// create a copy so the global var is unaffected
		var helpers = {};
		var globalHelpers = self.helpers();
		for(var name in globalHelpers) {
			if(globalHelpers.hasOwnProperty(name)) {
				helpers[name] = globalHelpers[name];
			}
		}
		for(var name in _helpers) {
			if(_helpers.hasOwnProperty(name)) {
				helpers[name] = _helpers[name];
			}
		}
		
		// run renderer with data and helpers
		var result = _renderer(data, helpers);
		
		return result;
	};

	/**
	 * Escape a template by adding backslashes to \ and "
	 */
	var escape = function(template)
	{
		return template.replace(/(\"|\\)/g, function(match, character) {
			return "\\" + character;
		});
	};


	/**
	* Create a renderer function
	*/
	var renderer = function(template, asString)
	{
		// strip newlines from template
		template = template.replace(/(\r\n|\n|\r)/gm, '');
		
		// get the regex matchers
		var matchers = self.matchers();
		
		// Create compiler function
		// Start with emprty string and specialchars function
		var compiler = 'var htmlSpecialChars = function(string){ return String(string).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/\'/g,"&#039;"); }\n'
		+ 'var compiled = "";\n'
	   // Localize given vars
		+ 'with(__data__) {\n'
		// start receiving strings
		+ 'compiled += "'

		// add the parsed template
		+ template 
		.replace(matchers.condition, condition)
		.replace(matchers.section, section)
		.replace(matchers.partial, partial)
		.replace(matchers.literal, literal)
		.replace(matchers.raw, raw)
		.replace(matchers.output, output)

		// close the string
		+ '";\n'
		// close with
		+ '}\n' 
		// return result
		+ 'return compiled;';

		if(asString) {
			// return the rederer as a string, used by partials
			return compiler;
		} else {
			// create function
			return new Function('__data__', '__helpers__',  compiler);
		}
	};	
	

	var condition = function(match, token, condition) {
		// end if
		if(token === '/if' || token === 'endif' || token === '/?') {
			return '";\n }\n compiled += "';
		}
		// else
		if(token === 'else' || token === 'elseif ' || token === '??') {
			if(typeof(condition) === 'undefined' || condition === null || condition === ''){
				// else
				return '";\n } else {\n compiled += "';
			} else 	if( /^[a-zA-Z0-9\_]+$/g.test(condition) ){
				// test for isset varialbe
				return '";\n } else if (typeof(' + condition + ') !== "undefined" && ' + condition + ' !== null && ' + condition + ') {\n compiled += "';
			} else {
				return '";\n } else if (' + condition.replace(/\\"/g, '"') + ' ) {\n compiled += "';
			}
		}
		// still here? if()
		if( /^[a-zA-Z0-9\_]+$/g.test(condition) ){
			// test for isset variable
			return '";\n if (typeof(' + condition + ') !== "undefined" && ' + condition + ' !== null && ' + condition + ') {\n compiled += "';
		} else {
			return '";\n if (' + condition.replace(/\\"/g, '"') + ' ) {\n compiled += "';
		}
	};
	

	var section = function(match, token, variable, name1, name2) 
	{
		// Section open
		if(token === '~' || token === '#' || token === 'each ' || token === 'filter ') {
			// check if there is a variable. if not, someone wrote {{ filter }} 
			// and meant to print the 'filter' var. 
			// Just return the whole match so it can be consumed later
			if(! variable) {
				return match;
			}

			// increment section
			_section++;

			if(token === '~' || token === 'filter ') {
				// This is a filter section: save filter name
				var fragment = '";\n var __filter__' + _section + ' = "' + variable + '";\n'
				// save the current state of compiled
				+ 'var __compiled__' + _section + ' = compiled;\n'
				// fake opening accolades to match later
				+ '{\n{\n'
					// start a new, nested compiled variable
					+ 'compiled = "';
			} else {
				// This is a loop section
				var fragment = '";\n if (Object.prototype.toString.call(' + variable + ') !== "[object Object]" && Object.prototype.toString.call(' + variable + ') !== "[object Array]"){\n'
					// convert non array or non object to array
					+ 'var __section__' + _section + ' = [].concat(' + variable + ');\n'
				+ '} else {\n'
					// just use the array/object
					+ 'var __section__' + _section + ' = ' + variable + ';\n'
				+ '}\n'

				// loop through array or object
				+ 'for(var __index__' + _section + ' in __section__' + _section + ') {\n'

					// check if own property and not prototype property
					+ 'if(__section__' + _section + '.hasOwnProperty(__index__' + _section + ')) {\n';
					
					
						// add named variable for key and value
						if(typeof(name2) === 'string' && name2 !== '') {
							// section as name1:name2 given
							fragment += 'var ' + name1 + ' = __index__' + _section + ';\n';
							fragment += 'var ' + name2 + ' = __section__' + _section + '[__index__' + _section + '];\n';
						} else if(typeof(name1) === 'string' && name1 !== '') {
							// section as name1 given
							fragment += 'var ' + name1 + ' = __section__' + _section + '[__index__' + _section + '];\n';
						}

						// next compiled
						fragment += 'compiled += "';
			}
			return fragment;
		}

		// Close a section
		if(token === '/' || token === 'endeach' || token === 'endfilter') {
			// close loop(){} and if(){} or faked sections started by filter
			var fragment = '";\n};\n};\n'

			+ 'if(typeof(__filter__' + _section + ') !== "undefined" && __filter__' + _section + ' !== null){\n'
				// if there is an active filter going on, run it on the current compiled string. the concat it to the original string
				+ 'compiled = __compiled__' + _section + ' + __helpers__[__filter__' + _section +  '](compiled);\n'
			+ '}\n'
			// unset section filter  to prevent it from being active in other section of the same level
			+ '__filter__' + _section + ' = null;\n'
			+ 'compiled += "';

			// decrement section
			_section--;

			// done
			return fragment;
		}
	};
	
	
	var partial = function(match, variable, vars) {
		// get partial by variablename
		// put it in the template with empty input as it will only need to use variables from the scope above it
		var partial = '(function(__data__, __helpers__) {\n' + renderer(_partials[variable], true) + '\n})';

		if(typeof(vars) === 'string' && vars !== '') {
			partial += '(' + vars.replace(/\\"/g, '"') + ', __helpers__)';
		} else {
			partial += '({}, __helpers__)';
		}
		return '";\n compiled += ' + partial + ';\n compiled += "';
	};
	

	var literal = function(match, literal) {
		return '";\n ' + literal.replace(/\\"/g, '"') + '\n; compiled += "';
	};
	
	var raw = function(match, expr) {
		return output(match, expr, true);
	};
	
	
	var output = function(match, expr, raw)
	{
		// split out the expression in parts separated by |, but not ||
		// save the ||'s and split on the |
		var parts = expr.replace(/\|\|/g, '___or___').split('|');
		// the variable is the first part, recreate the ||'s here.
		var expression = parts.shift().replace('___or___', '||').trim();
		// the rest of the parts are the filters
		var filters = [];
		for(var i = 0; i < parts.length; i++){
			var filter = {
				name: null,
				args: false
			};
			// recreate ||'s and split on the first (
			var pieces = parts[i].replace('___or___', '||').split('(', 2);
			// first part is the filtername: remove non function chars
			filter.name = pieces[0].replace(/[^a-zA-Z0-9\_]+/g, '');
			if(pieces.length == 2){
				// if there is a second part, these are one or more arguments, remove trailing ) (and spaces)
				filter.args = pieces[1].replace(/[\s\)]+$/g, '');
			} 
			filters.push(filter);
		}

		if(/^[a-zA-Z_]+$/.test(expression)) {
			// just a variable
			var fragment = '";\n var __value = typeof(' + expression + ') !== "undefined" ? ' + expression + ' : "";\n';
		} else {
			// it is an actual expression
			fragment = '";\n var __value = eval("' + expression.replace(/\"/g, '\"') + '");\n';
		}
	
		for(var i = 0; i < filters.length; i++) {
			fragment += '__value = __helpers__["' + filters[i].name + '"](__value' + (filters[i].args ? (', ' + filters[i].args) : '') + ');\n';
		}

		fragment += 'compiled += typeof(__value) !== "undefined" ? ' + (  raw !== true ? 'htmlSpecialChars' : '')  + '(__value) : "";\n'
		+ 'compiled += "';

		return fragment;
	};
});

//_____ yellow/Arr.js _____//

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

//_____ manager/Main.js _____//

define('manager.Main')
.use('manager.Dialog')
.as(function(y, Dialog){
	
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;
		
		// set dialog language
		Dialog.lang = scope.data('lang');
		
		// fixed header
		var header = _scope.fetch('header-fixed');
		header.css({
			position: 'relative',
			width: '100%'
		});
		var width = header.outerWidth();
		header.css({
			position: 'fixed',
			top: 0,
			width:  width + 'px'
		});
		
		
		// adjust header ehight
		var adjustHeight = function(height){
			header.parent().css('padding-top', height + 'px');
		}
		
		// First time
		var height = header.outerHeight();
		adjustHeight(height)
		
		// Keep checking
		setInterval(function(){
			var newHeight = header.outerHeight();
			if(newHeight != height) {
				adjustHeight(newHeight);
				height = newHeight;
			}
		}, 500);
		
		
		// keepalive session
		setInterval(function(){
			y.ajax(scope.data('keepalive'))
		}, 1000* 60 * 5)
	}
});


//_____ manager/Form.js _____//

define('manager.Form')
.use('manager.Post')
.as(function(y, Post) {
	
	/**
	 * Form element
	 */
	var _scope;


	/**
	 * Make scope available
	 */
	this.start = function(scope) 
	{
		_scope = scope;
		var self = this;
		_scope.submit(function(e){
			e.preventDefault();
			if(_scope.data('submit')) {
				self.submit();
			}
		});
	}
	
	
	/**
	 * Submit the form by getting its values, serializing them
	 * creating a background form and submitting that
	 */
	this.submit = function()
	{
		Post.make(_scope.attr('action')).submit(this.values());
	}
	
	
	/**
	 * Submit the form with ajax, with three handlers
	 */
	this.ajax = function(done, fail, always)
	{
		Post.make( _scope.attr('action')).ajax(this.values(), done, fail, always);
	}
	
	
	/**
	 * Get the value for each element
	 */
	this.values = function()
	{
		var values = {};
		var elements = _scope.data('elements');
		elements = y.isObject(elements) ? elements : {};
		for(var key in elements) {
			var element = _scope.fetch(elements[key]);
			if(element.length > 0) {
				values[key] = element.invoke('value');
			}
		}
		return values;
	}
	
	
	/**
	 * Get a registered element by key 
	 * @param string key
	 * @returns object
	 */
	this.element = function(key)
	{
		var elements = _scope.data('elements');
		elements = y.isObject(elements) ? elements : {};
		if(y.isSet(elements[key])) {
			return _scope.fetch(elements[key]);
		}
	}
});

//_____ manager/form/element/Hidden.js _____//

define('manager.form.element.Hidden')
.as(function(y) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		_scope.find('input').val(scope.data('value'));
	}
	
	this.value = function()
	{
		return _scope.find('input').val();
	}
});

//_____ manager/Post.js _____//

define('manager.Post')
.use('yellow.View')
.template('form', '<form action="{{ action }}" method="POST"{% if target %} target="{{ target }}"{% endif %}></form>')
.template('hidden', '<input type="hidden" />')
.as(function(y, View, template) {
	
	var _url;
	
	var _maxDepth = 10;
	
	this.start = function(url) 
	{
		_url = url;
	}
	
	
	this.submit = function(data, target)
	{
		// create a temp form
		var form = View.make(template('form')).element({
			action: _url,
			target: target
		});
		// populate the form with serialized post values
		var values = serialize(data);
		for(var i = 0; i < values.length; i++) {
			var hidden = View.make(template('hidden')).element();
			hidden.attr('name', values[i].name);
			hidden.val(values[i].value);
			form.append(hidden);
		}
		// submit and remove
		y('body').append(form);
		form.submit().remove();
	}
	
	
	this.ajax = function(data, done, fail, always)
	{
		y.ajax(_url, {
			data: serialize(data),
			type: 'POST',
			dataType: 'JSON'
		}).done(function(data) {
			if(y.isFunction(done)) {
				done(data)
			}
		}).fail(function(data) {
			if(y.isFunction(fail)) {
				fail(data)
			}
		}).always(function(data) {
			if(y.isFunction(always)) {
				always(data)
			}
		})
	}
	
	
	/**
	 * serialize a js object into post format
	 */
	var serialize = function(data, serialized, name, depth)
	{
		if(! y.isSet(serialized)) {
			// first run
			serialized = [];
			name = ''
		}
		
		if(! y.isSet(depth)) {
			// first run
			depth = 0;
		}
		
		// dont exceed max depth, as it will create an enormous post array.
		// stop parsing: just return
		depth++;
		if(depth > _maxDepth) {
			return serialized;
		}
	
		if(y.isObject(data)) {
			// serialize object, append [key] to name
			for(var key in data) {
				if(name === '') {
					serialized = serialize(data[key], serialized, key, depth);	
				} else {
					serialized = serialize(data[key], serialized, name + '[' + key + ']', depth);	
				}
			}
		} else if (y.isArray(data)) {
			// serialize array, append [i] to name
			for(var i = 0; i < data.length; i++) {
				serialized = serialize(data[i], serialized, name + '[' + i + ']', depth);	
			}
		} else {
			// a scalar: we can push it to the post array
			serialized.push({ name: name, value: data})
		}
		return serialized;
	}
	
	
	/**
	 * unserialize post format to object
	 */
	var unserialize = function(values)
	{
		var data = {};
		for(var i = 0; i < values.length; i++) {
			var name = values[i].name;
			var value = values[i].value;

			var base = name.split('[')[0];
			if(! data[base]) {
				data[base] = '__empty__';
			}
			var put = data;
			var current = base;
			var regex = /\[([^\]]*)\]/g;
			var match;
			while (match = regex.exec(name)) {
				if(/[0-9]+/.test(match[1])) {
					var key = Number(match[1]);
					if(put[current] === '__empty__') {
						put[current] = [];
					}
					if(put[current] instanceof Array) {
						put[current][key] = '__empty__';
						put = put[current];
						current = key;
					} else {
						break;
					}
				} else if(match[1] === '') {
					if(put[current] === '__empty__') {
						put[current] = [];
					}
					if(put[current] instanceof Array) {
						put[current].push('__empty__');
						put = put[current];
						current = put.length - 1;
					} else {
						break;
					}
				} else {
					var key = match[1];
					if(put[current] === '__empty__') {
						put[current] = {};
					}
					if(put[current] instanceof Object && ! (put[current] instanceof Array)) {
						put[current][key] = '__empty__';
						put = put[current];
						current = key;
					} else {
						break;
					}
				}
			}
			put[current] = value;
		}
		return data;
	}
});





//_____ manager/form/element/Text.js _____//

define('manager.form.element.Text')
.as(function(y) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		_scope.val(scope.data('value'));
	}
	
	this.value = function(value)
	{
		if(y.isSet(value)) {
			_scope.val(value)
		}
		return _scope.val();
	}
});

//_____ manager/form/element/Submit.js _____//

define('manager.form.element.Submit')
.as(function(y) {
	this.start = function(scope) 
	{
		scope.click(function(){
			scope.fetch('form', 'closest').invoke('submit');
		});
	}
});

//_____ manager/Navigation.js _____//

define('manager.Navigation')
.as(function(y) {
	this.start = function(scope) 
	{
		var index = sessionStorage.getItem('expanded');
		
		
		if(index || index === 0) {
			var item = scope.fetch('expandable').eq(index);
			item.addClass('expanded');
			item.fetch('more').hide();
			item.fetch('less').show();
			item.fetch('expanded').show();
		}
								
		scope.fetch('button').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			
			var target = y(this).attr('target');
			if(target) {
				window.open(y(this).attr('href'), target);
			} else {
				document.location.href = y(this).attr('href');
			}
		});
		
		
		scope.fetch('expand').click(function(e){
			scope.addClass('expanded');
		});
		
		scope.fetch('overlay').click(function(e){
			e.stopPropagation();
			e.preventDefault();
			scope.removeClass('expanded');
		});
		
		scope.fetch('expandable').click(function(e) {
			scope.fetch('more').show();
			scope.fetch('less').hide();
			
			scope.fetch('expanded').slideUp(100);
			
			
			var self = y(this);
			var expanded = self.hasClass('expanded');
			
			scope.fetch('expandable').removeClass('expanded');
			
			if(! expanded) {
				self.addClass('expanded');
				self.fetch('more').hide();
				self.fetch('less').show();
				self.fetch('expanded').slideDown(100);
				
				// store the expanded state
				sessionStorage.setItem('expanded', scope.fetch('expandable').index(self));
			}
		});
	}
	
});


//_____ manager/Index.js _____//

define('manager.Index')
.use('yellow.View')
.use('manager.Loading')
.as(function(y, View, Loading){
	
	var _scope;
	var _url = '';
	var _url_move = '';
	var _filter = [];
	var _filters = [];
	var _sort = [];
	var _search = [];
	var _skip = 0;
	var _amount = 0;
	var _total = false;
	var _tree = false;
	var _sortable = false;
	var _lock = false;
	var _url_locked = false;
	
	this.start = function(scope)
	{
		// globalize vars
		_scope = scope;
		_url = scope.data('url');
		_url_move = scope.data('url_move');
		_filter = scope.data('filter');
		_filters = scope.data('filters');
		_sort = scope.data('sort');
		_search = scope.data('search');
		_skip = scope.data('skip');
		_amount = scope.data('amount');
		_tree = scope.data('tree');
		_sortable = scope.data('sortable');
		_lock = scope.data('lock');
		_url_locked = scope.data('url_locked');
		
		// create elements
		index();
		
		// add search and filters
		if(! _tree && ! _sortable) {
			search();
			filters();
		}
	
		// lock
		if(_lock) {
			lock();
		}
	
		// start load
		load();
	}
	
	
	
	/**
	 * Start up search field
	 */
	var search = function()
	{
		var search = View.make(_scope.template('search')).element({
			value: _search
		})
		.appendTo(_scope.fetch('search'));

		var remove = search.fetch('remove');
		var input = search.fetch('input');
		var submit = search.fetch('submit');
		
		input.on('keyup', function (e) {
			if (e.keyCode == 13) {
				submit.click();
			}
		});
		
		submit.click(function(){
			var val = input.val();
			if(val !== _search){
				// set searchval
				_search = val;
				// recalculate total
				_total = false;
				// start a beginning
				_skip = 0;
				// load new data
				load();
			}

			remove.detach();
			if(val){
				remove.prependTo(search);
			}
		});
		
		remove.click(function(){
			input.val('');
			submit.click();
		});
		
		
		input.keyup(function (e) {
            if (e.keyCode === '13') {
				e.preventDefault();
				e.stopPropagation();
                submit.click();
            }
        });
		
		if(!_search){
			remove.detach();
		}
	};
	
	
	/**
	 * Start up filters
	 */
	var filters = function()
	{
		for(var name in _filters){
			// create a filterbox
			var filter = View.make(_scope.template('filter'))
			.element({
				name : name,
				options : _filters[name]
			})
			.appendTo(_scope.fetch('filters'));
	
			// set change handler
			filter.fetch('select')
			.change('dit is data', function(e){
				var el = y(this);
				var filter = el.fetch('filter', 'parent');
				var remove = filter.fetch('remove');
				var name = el.attr('name');
				var val = el.val();
				if(val === '-1') {
					// no value selected
					remove.hide();
					filter.removeClass('input-group');
					delete(_filter[name]);
				} else {
					// value selected
					remove.show();
					filter.addClass('input-group');
					_filter[name] = val;
				}
				// only load if allowed
				if(filter.data('load')){
					// recalculate total
					_total = false;
					// start a beginning
					_skip = 0;
					// load new data
					load();
				}
			});
			
			// remove button
			filter.fetch('remove')
			.click(function(e){
				var filter = y(this).fetch('filter', 'parent');
				filter.fetch('select')
				.val('-1')
				.change();
			});
	
			// init value
			// dont load on the initial change
			filter.data('load', false);
			if(y.isSet(_filter[name])){
				filter.fetch('select')
				.val(_filter[name])
				.change();
			} else {
				filter.fetch('select')
				.val('-1')
				.change();
			}
			// from now on load data on change
			filter.data('load', true);
		}
	};
	
	
	/**
	 * Create sortable selectable container for items
	 */
	var index = function()
	{
		// create list
		var element = View.make(_scope.template('list')).element();
		
		// append it
		element.appendTo(_scope.fetch('list'));
		
		// make sorters work
		element.fetch('sort').each(function(){
			var element = y(this);
			var column = element.data('column');
			var direction = element.data('direction');
			
			if(y.isSet(_sort[column])){
				direction = _sort[column];
				element.fetch(direction).show();
			} 
			element.click(function(e){
				e.preventDefault();
				
				// turn off all arrows
				element.fetch('asc').hide();
				element.fetch('desc').hide();
				
				// change direction
				direction = direction == 'asc' ? 'desc' : 'asc';
				
				// turn on this one
				element.fetch(direction).show();
				
				// set new state
				_sort = {};
				_sort[column] = direction;
				_skip = 0;
				
				//load again
				load();
				
				// paginate
				if(! _tree && ! _sortable) {
					pagination();
				}
			})
		})
	};
	
	
	/**
	 * periodically get locks
	 * @returns
	 */
	var lock = function()
	{
		setInterval(function() {
			var ids = []
			 _scope.fetch('item').each(function(){
				 ids.push(y(this).data('id'))
			});
			y.ajax(_url_locked, {
				dataType: 'json',
				type: 'POST',
				data: {id: ids.join(',')}
			})
			.done(function(data){
				_scope.fetch('item').each(function(){
					if(data[y(this).data('id')]) {
						y(this).fetch('locked').text(data[y(this).data('id')]).show();
					} else {
						y(this).fetch('locked').hide();
					}
			   });
			});
		}, 5 * 1000)
	}
	
	
	/**
	 * Load the data
	 */
	var load = function()
	{
		var container = _scope.fetch('container');
		// create url
		var urlSort = [];
		for(var column in _sort){
			urlSort.push(column + '=' + _sort[column]);
		}
		urlSort = urlSort.join(';');
		
		
		var urlFilter = [];
		for(var column in _filter){
			urlFilter.push(column + '=' + _filter[column]);
		}
		urlFilter = urlFilter.join(';');
		
		
		var url = _url
		.replace('{{count}}', _total === false ? '1' : '0')
		.replace('{{amount}}', _amount ? _amount : -1)
		.replace('{{skip}}', _skip)
		.replace('{{filter}}', urlFilter)
		.replace('{{sort}}', urlSort)
		.replace('{{search}}', _search);

		// show loading
		Loading.show();
		
		// start loading
		y.ajax(url, {
			dataType: 'json'
		})
		.done(function(data){
			
			// empty list
			container.empty();
			
			// add new items
			add(data.items);
			
			// hide loading
			Loading.hide();
			
			// add drag handlers
			if(_sortable) {
				sortable(container);
			}
		
			// build the pagination if total wasn't known yet
			// this happens only the first time
			if(_total === false){
				// set the total
				_total = data.total;
				if(! _tree && ! _sortable) {
					// build the pagination
					pagination();
				}
			}
		});
	};
	
	
	var add = function(items, before)
	{
		// get the container
		var container = _scope.fetch('container');
		
		// create the items
		var view = View.make(_scope.template('item'));

		var map = {};
		for(var i = 0; i < items.length; i++){
			// render element
			var element = view.element({
				item: items[i]
			});
			// add item data for use by other parts
			element.data('item', items[i].data);
			
			// mark locked
			if(_lock && items[i].data.editor) {
				element.fetch('update').invoke('lock', items[i].data.editor.screenname)
			}
			
			if(_tree) {
				// build a map for a tree
				map['id_' + items[i].data.id] = element;
				element.data('parentId', items[i].data.parent_id)
			} else {
				// just add it for a regular table
				if(before) {
					element.prependTo(container);
				} else {
					element.appendTo(container);
				}
			}
		}	

		if(_tree) {
			// build tree
			for(var id in map) {
				var element = map[id];
				var parentId = element.data('parentId');
				if(parentId == 0) {
					// root element
					if(before) {
						element.prependTo(container);
					} else {
						element.appendTo(container);
					}
				} else if(y.isSet(map['id_' + parentId])) {
					// child element
					element.appendTo(map['id_' + parentId].fetch('children').first());
				}
			}
		}
	}
	
	
	/**
	 * public prepend
	 * @param array items
	 * @returns void
	 */
	this.add = function(items, before)
	{
		add(items, before);
	}
	
	
	var sortable = function(container)
	{
		container.nestedSortable({
			handle: '[y-name=move]',
			items: '[y-name^=item]',
			helper: 'clone',
			opacity: .6,
			revert: 250,
			placeholder: 'placeholder',
			//toleranceElement: '> div',
			listType: container.prop('tagName').toLowerCase(),
			isTree: _tree
		}).on('sortstop', function(event,ui) {
			// get the moved id
			var id = ui.item.data('id');

			// get the item after which it moved
			var after = 0;
			var prev = ui.item.prev();
			if(prev.length > 0){
				after = prev.data('id');
			}
			
			// get the parent in which it moved
			var parent = '';
			if(_tree) {
				var closest = ui.item.fetch('item', 'parents').first();
				if(closest.length > 0){
					parent = closest.data('id');
				} else {
					parent = 0;
				}
			}


			// send out ajax req
			y.ajax(
				_url_move.replace('{{id}}', id)
				.replace('{{after}}', after)
				.replace('{{parent}}', parent), 
				{
					dataType: 'json',
					type : 'POST',
					data: {
						csrf: _scope.data('csrf')
					}
				}
			);
		});
	}


	/**
	 * Build pagination
	 */
	var pagination = function()
	{
		if(_amount) {
			// renderdata
			var data = {
				first: _skip + 1,
				last: _total > _amount ? _skip + _amount : _total,
				total: _total,
				previous: false,
				next: false
			};
			
			if(_total > _amount){
				// get steps
				var steps = Math.ceil(_total/_amount);

				// add  previous
				if(_skip > 0){
					data.previous = (_skip - _amount < 0) ? 0 : (_skip - _amount)
				}
				
				// add next
				if(_skip < (steps - 1) * _amount){
					data.next = _skip + _amount;
				} 
			}
			// render pagination
			var element = View.make(_scope.template('pagination')).element(data);
			
			// add actions
			element.fetch('button').click(function(e){
				// no real click
				e.preventDefault();
				// set offset
				_skip = y(this).data('skip');
				// load new content
				load();
				// build it again
				pagination();
			})
			
			
			// add it to the html
			_scope.fetch('pagination')
			.empty()
			.append(element);

		}
	}
});

//_____ manager/Loading.js _____//

define('manager.Loading')
.use('yellow.View')
.use('manager.Overlay')
.template('loading' , '\
<div y-name="__loading__" class="loading" style="width:100%; height: 100%">\
</div>')

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
			_overlay = y.outer().get('manager.Overlay').make(loading, {
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

//_____ manager/Overlay.js _____//

define('manager.Overlay')

.use('yellow.View')

.template('overlay','<div class="overlay" style="position:fixed; top:0; left: 0; z-index:1000; background:rgba(0,0,0,0.2); width:100%; height: 100%;">\
	<div class="overlay-background" y-name="background" style="position: fixed;"></div>\
	<div class="overlay-container" y-name="container" style="position: absolute; "></div>\
</div>')


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

//_____ manager/index/batch/Delete.js _____//

define('manager.index.batch.Delete')
.use('manager.Loading')
.use('manager.Dialog')
.as(function(y, Loading, Dialog) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
	}
	
	
	/**
	 * public delete
	 */
	this.delete = function(items) {
		var confirm = Dialog.confirm(_scope.data('title'), _scope.data('message'), function(){
			confirm.remove();
			Loading.show();
			var queue = items.length;
			for(var i = 0; i < items.length; i++){
				y.ajax(_scope.data('url').replace('{{id}}', items[i].data('id')), {
					dataType: 'json',
					type : 'POST',
					data: {
						csrf: _scope.data('csrf')
					}
				})
				.always(function(){
					queue--;
					if(queue === 0) {
						Loading.hide();
					}
				})
				.done((function(item) {
					return function(data){
						if(data.success) {
							item.remove();
						}
					}
				})(items[i]));
			}
		});
	}
});

//_____ manager/Dialog.js _____//

define('manager.Dialog')
.use('yellow.View')
.use('manager.Post')
.use('manager.Overlay')

// templates
.template('dialog' ,
'<div class="dialog">' +
	'<div class="dialog-content">' +
		'<i class="icon dialog-close clickable" href="#" y-name="close">close</i>' +
		'<h3>{{title}}</h3>' +
		'<div class="dialog-body">' +
			'<p>{{{body}}}</p>' +
		'</div>' +
		'<div class="dialog-buttons" y-name="buttons" ></div>' +
	'</div>' +
'</div>')

.template('iframe' ,
'<div class="dialog">' +
	'<i class="icon dialog-close clickable" href="#" y-name="close">close</i>' +
	'<iframe width="100%" height="100%" name="{{name}}" y-name="iframe" frameborder="0"></iframe>' +
'</div>')

.template('button' , '<a href="#" target="{{ target}}" class="btn btn-{{type}} mr-1" role="button">{{label}}</a>')



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
			width: 600,
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
			width: 600,
			height: 300,
		});
		return dialog;
	},
	iframe: function(src, data){
		var dialog = this.make({
			template: 'iframe',
			src: src,
			data: data
		});
		return dialog;
	},
})


.as(function(y, View, Post, Overlay, template)
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
				
				// Close function
				var close = function() {
					if(y.isFunction(_config.close)){
						_config.close();
					}
					_this.remove();
				}
				
				var iframe = dialog.fetch('iframe').get(0);
				if(iframe && y.isFunction(iframe.contentWindow.onbeforeunload)) {
					// When there's an iframe with a beforeunload, trigger the beforeunload with a callback
					// The iframe will call the calback when it's ok.
					// We're taking a chance here: we could have an iframe with beforeunload that doesnt handle the callback
					y(iframe.contentWindow).trigger('beforeunload', close)
				} else {
					// just close
					close();
				}
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
		_overlay = y.outer().get('manager.Overlay').make(dialog, _config);
		if(_config.template == 'iframe' && _config.data) {
			// submit data to iframe
			Post.make(_config.src).submit(_config.data, _config.name)
		} else if (_config.template == 'iframe') {
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

//_____ manager/index/Batch.js _____//

define('manager.index.Batch')
.as(function(y, Loading, Dialog) {

	var _scope;
	
	var _current;
	
	this.start = function(scope) 
	{
		_scope = scope;
		
		// make selector work
		scope.fetch('select-all').change(function(){
			if(y(this).is(':checked')) {
				scope.fetch('index', 'closest').fetch('select').prop('checked',true);
			} else {
				scope.fetch('index', 'closest').fetch('select').prop('checked', false);
			}
		});
		
		
		scope.fetch('select').change(function(){
			_current = y(this).find(':selected');
			if(_current.data('apply')) {
				scope.fetch('apply').show();
			} else {
				scope.fetch('apply').hide();
			}
		});
		
		
		scope.fetch('apply').click(function(){
			var selected = [];
			_scope.fetch('index', 'closest').fetch('item').each(function(){
				var item = y(this);
				if(item.fetch('select').is(':checked')) {
					selected.push(item);
				}
			});
		
			var invoke = _current.data('invoke');
			if(invoke) {
				_current.invoke(invoke, selected);
			}
		});
	}
});

//_____ manager/Update.js _____//

define('manager.Update')
.use('yellow.View')
.use('manager.Loading')
.use('manager.Message')
.use('manager.Dialog')
.use('manager.Post')
.use('manager.Callback')
.as(function(y, View, Loading, Message, Dialog, Post, Callback)
{
	var _scope;
	var _id;
	var _status;
	var _state;
	var _skipUnload = false;
	
	
	this.start = function(scope)
	{
		_scope = scope;
		_id = scope.data('id');
		_status = scope.data('status');
		_state = state();


		// lock
		if(scope.data('lock')) {
			if(_id) {
				y.ajax(scope.data('url_locked'), {
					dataType: 'JSON',
					type: 'POST',
					data: {
						id: _id
					}
				}).done(function(data){
					if(data[_id]) {
						// item is locked
						Dialog.confirm(scope.data('claim_title'), scope.data('claim_message').replace(/\{\{username\}\}/g, data[_id]), function(){
							claimLock(pollLock);
							restore();
						}, function(){
							leave();
						})
					} else {
						// item is not locked
						claimLock(pollLock);
						restore();
					}
				});
			}
		} else if(_id) {
			// no locks for this module
			restore();
		}


		// catch form changed outbound click
		y('a[href]').click(function(e){
			var href = y(this).attr('href');
			if(href.indexOf('#') === -1){
				if(state() !== _state){
					Dialog.confirm(scope.data('abandon_title'), scope.data('abandon_message'), function(){
						// user agreed: skip the beforeunload
						_skipUnload = true;
						// go to the clicked url
						window.location.href = href;
					});
					e.preventDefault();
				}
			}
		});
		
		
		// catch unload other than click
		window.onbeforeunload = function(e, callback) {
			if(state() !== _state && !_skipUnload){
				if(y.isFunction(callback)) {
					// A callback was supplied when triggering the beforeunload, 
					// we're in a iframe that is closing: launch a dialog and use the callback
					Dialog.confirm(scope.data('abandon_title'), scope.data('abandon_message'), function(){
						// call the callback when done
						callback();
					});
				} else {
					// Regular actual unload: launch a browser alert by returning text
					return scope.data('abandon_message').replace(/\<br \/\>/g, '\n');
				}
			} else if(y.isFunction(callback)) {
				// A callback was supplied when triggering the beforeunload, 
				// There is no alert needed, so we can proceed by calling the callback
				callback();
			}
		};
		
		
		// regular save button
		scope.fetch('btn-save').click(function(e){
			e.preventDefault();
			save(
				saveDoneSuccess, 
				saveDoneError, 
				saveFail, 
				saveAlways
			);
		});
		// route form submit through the save button
		scope.find('form').submit(function(e){
			e.preventDefault();
			e.stopPropagation();
			scope.fetch('btn-save').click();
		});
		
		// update button: first save, then go to callback url
		scope.fetch('btn-update').click(function(e){
			e.preventDefault();
			var vals = values();
			vals.id = _id;
			save(
				function(){
					_state = state();
					// do save always here, because, the Callback.invoke will remove the popup
					// so saveAlways will never be reached
					saveAlways();
					Callback.invoke(scope.data('callback'), vals)
				},
				saveDoneError,
				saveFail, 
				saveAlways
			);
		});
		

		// select button: check abandon, then go to callback url
		scope.fetch('btn-select').click(function(e){
			e.preventDefault();
			var vals = values();
			vals.id = _id;
			if(state() !== _state){
				Dialog.confirm(scope.data('abandon_title'), scope.data('abandon_message'), function(){
					// user agreed: skip the beforeunload
					_skipUnload = true;
					// go to the clicked url
					Callback.invoke(scope.data('callback'), vals)
				});
			} else {
				Callback.invoke(scope.data('callback'), vals)
			}
		});
		

		// delete button
		scope.fetch('btn-delete').click(function(e){
			e.preventDefault();
			Dialog.confirm(scope.data('delete_title'), scope.data('delete_message'), function(){
				y.ajax(scope.data('url_delete').replace('{{id}}', _id),{
					type : 'POST',
					dataType : 'json',
					data: {
						csrf: scope.data('csrf')
					}
				}).always(function(){
					leave();
				});
			});
		});
		
		// preview button
		scope.fetch('btn-preview').click(function(e){
			window.open('', 'preview');
			
			// create base64 encoded string. Escape multibyte chars.
			// https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding#Solution_2_%E2%80%93_escaping_the_string_before_encoding_it
			var contents =  btoa(encodeURIComponent(JSON.stringify(values())).replace(/%([0-9A-F]{2})/g,
				function (match, p1) {
					return String.fromCharCode('0x' + p1);
			}));
			/// create base64 encoded string, because chrome will not allow raw iframes an script to go through post
			Post.make(scope.data('url_preview')).submit({values: contents}, 'preview');
		});
		
		// history button
		history(scope.data('revisions'), scope.data('revision'));
		
		
		// status button
		var btnStatus = scope.fetch('btn-status');
		btnStatus.fetch('live').click(function(){
			_status = 'edit';
			btnStatus.fetch('live').hide();
			btnStatus.fetch('edit').show();
		});
		
		btnStatus.fetch('edit').click(function(){
			_status = 'live';
			btnStatus.fetch('live').show();
			btnStatus.fetch('edit').hide();
		});
	}
	
	
	this.id = function()
	{
		return _id;
	}
	
	
	var claimLock = function(callback)
	{
		// claim lock
		y.ajax(_scope.data('url_lock').replace('{{id}}', _id) + '?force=1', {
			type: 'POST',
			data: {
				csrf: _scope.data('csrf'),
				force: 1
			}
		}).done(function(){
			// lock claimed, do next thing
			callback();
		});
	}
	
	
	var pollLock = function()
	{
		var poll = function() {
			if(_id) {
				// try to get lock
				y.ajax(_scope.data('url_lock').replace('{{id}}', _id), {
					dataType: 'json',
					type: 'POST',
					data: { 
						csrf: _scope.data('csrf') 
					}
				}).done(function(data){
					if(! data.success) {
						// failed to get lock
						clearInterval(interval);
						// rescue the data
						rescue();
						// message
						Dialog.alert(_scope.data('claimed_message').replace(/\{\{username\}\}/g, data.username), function(){
							leave();
						})
					}
				});
			}
		}
		
		// poll once, after that at an interval
		poll();
		var interval = setInterval(function(){
			poll();
		}, 10 * 1000);
	}
	
	
	var leave = function()
	{
		// skip check
		_skipUnload = true;
		// go back
		window.location.href = _scope.data('url_back');

	}
	
	
	var pollAuthenticated = function()
	{
		
	}
	
	
	var rescue = function()
	{
		var current = state();
		if(current !== _state) {
			var current =  JSON.parse(current);
			var previous = JSON.parse(_state);
			var diff = {}
			for(var key in current) {
				if(JSON.stringify(current[key]) !== JSON.stringify(previous[key])) {
					diff[key] = current[key];
				}
			}
			var key = _scope.data('module') + ':' + _id;
			localStorage.setItem(key, JSON.stringify(diff));
		}
	}
	
	
	var unrescue = function()
	{
		var key = _scope.data('module') + ':' + _id;
		localStorage.removeItem(key);
	}
	
	
	
	var restore = function()
	{
		var key = _scope.data('module') + ':' + _id;
		var rescued = localStorage.getItem(key);
		localStorage.removeItem(key);
		try {
			if(rescued) {
				rescued = JSON.parse(rescued);
				if (y.isObject(rescued)) {
					Dialog.confirm(_scope.data('restore_title'), _scope.data('restore_message'), function(){
						Post.make(_scope.data('url_restore').replace('{{id}}', _id)).submit(rescued);
					});
				}
			}
		} catch(e) {}
	}
	
	
	var save = function(doneSuccess, doneError, fail, always)
	{
		// get the form data
		var data = values();
		
		// add csrf
		data.csrf =_scope.data('csrf');

		// remove errors
		_scope.fetch('error').hide();
		_scope.fetch('group').removeClass('form-error-group');

		// show loading
		Loading.show();
		
		// rescue data
		rescue()

		// post with ajax
		Post.make(_scope.data('url_save').replace('{{id}}', _id)).ajax(
			data,
			function(response){
				if(response.success) {
					doneSuccess(response);
				} else {
					doneError(response);
				}
			},
			fail,
			always
		);
	}
	
	
	var saveDoneSuccess = function(response)
	{
		// save succesful: work with this new dataset as startingpoint
		_state = state();

		if(_id == 0){
			// set id
			_id = response.id;
			// change the title
			_scope.fetch('header').fetch('title-create').hide();
			_scope.fetch('header').fetch('title-update').show();

			// turn on the delete button
			_scope.fetch('header').fetch('delete').show();

			// turn on the version button
			_scope.fetch('header').fetch('version').show();

			// turn on the select button
			_scope.fetch('header').fetch('select').show();
			
			// update url
			y.window[0].history.replaceState({},'', 'update/' + _id);
			
		}
		// update the revisions
		if(response.revisions) {
			history(response.revisions, response.revisions.length > 0 ? response.revisions[0].revision : 0);
		}
		
		// show the seelct button (if it's there)
		_scope.fetch('btn-select').show();
		
		// show message
		Message.make(response.message, 'success');
	}
	
	
	var saveDoneError = function(response)
	{
		// show message
		Message.make(response.message, 'error');
		// show errors
		for(var key in response.errors){
			var group = _scope.fetch('element-' + key).fetch('group', 'closest');
			var error = group.fetch('error');
			error.show();
			error.text(response.errors[key])
			group.addClass('form-group-error')
		}
	}
	

	var saveFail = function(request, status, errorThrown){
		// show error message
		Message.make(errorThrown, 'error');
	}
	
	
	var saveAlways = function(){
		// always unrescue data when done
		unrescue();
		// always hide loading screen when done
		Loading.hide();
	};
	
	
	var values = function()
	{
		var data = _scope.fetch('form').invoke('values');
		data.status = _status;
		return data;
	}
	
	var state = function()
	{
		return JSON.stringify(values());
	}
	
	/**
	 * update histoty button
	 * @param array revisions
	 * @param int revision
	 * @returns void
	 */
	var history = function(revisions, revision)
	{
		var container = _scope.fetch('revisions');
		container.empty();
		if(revisions.length > 0){
			View.make(_scope.template('revisions')).element({
				revisions:revisions,
				current: revision,
				url:_scope.data('url_revision').replace('{{id}}', _id).replace('{{revision}}', '__revision__')
			}).appendTo(container);
		}
	}
});




//_____ manager/form/element/Slug.js _____//

define('manager.form.element.Slug')
.as(function(y)
{
	var _scope;

	this.start = function(scope)
	{
		_scope = scope;
		
		_scope.val(_scope.data('value'));
		
		var source = _scope.fetch('form', 'closest').fetch('element-' + scope.data('source'));
		var mode = _scope.data('value') == '' ? 'create' : 'update';
		var sync = false;

		source.focus(function(e){
			var value = _scope.val();
			// get alias
			var slug = prepare_uri(source.invoke('value'));
			// if the alias is the same as the title, or the alias is blank, it's ok to sync
			if( slug == value || value == ''){
				sync = true;
			} else {
				sync = false;
			}
		});
		

		source.change(function(e){
			// if we are creating and it's ok to sync
			if(mode == 'create' && sync){
				// set the value
				_scope.val(prepare_uri(source.invoke('value')))
			}
		});
		
		_scope.change(function(e){
			// when manually changing, show correct url
			_scope.val(prepare_uri(_scope.val()))

		});
		
	}
	

	this.value = function()
	{
		return _scope.val();
	}
	
	
	var prepare_uri = function(uri)
	{
		uri = uri.trim();
		uri = uri.replace(/ /g,'-');
		uri = iconv(uri);
		uri = uri.replace(/[^A-Za-z0-9-]/g, '');
		uri = uri.replace(/-+/g, '-');
		uri = uri.replace(/^-+|-+$/g, '');
		uri = uri.toLowerCase();
		return uri;
	}
	
	
	var iconv = function(s){
		var in_chrs = 'àáâãäçèéêëìíîïñòóôõöøùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝß';
		var out_chrs = 'aaaaaceeeeiiiinoooooouuuuyyAAAAACEEEEIIIINOOOOOOUUUUYs';
		var chars_rgx = new RegExp('[' + in_chrs + ']', 'g');
		var transl = {};
		
		var lookup = function (m) {
			if(m == 'ß'){
				return 'ss';
			} else {
				return transl[m] || m; 
			}
		};
		
		for (var i = 0; i < in_chrs.length; i++) {
			transl[in_chrs[i]] = out_chrs[i];
		}
		return s.replace(chars_rgx, lookup);
	}
});

//_____ manager/form/element/Date.js _____//

define('manager.form.element.Date')
.use('yellow.View')
.as(function(y, View) {
	
	var _scope;
	var _value;
	var _time;
	var _offset = 0;
	
	var _year;
	var _month;
	var _day;
	var _hour;
	var _minute;
		
	var _view;
	
	this.start = function(scope) 
	{
		_scope = scope;
		_value = scope.data('value');
		_time = scope.data('time');
		_offset = scope.data('offset');

		moment.locale(scope.data('lang'));

		// this is servertime
		var mom = moment(_value);

		// add offset to get displaytime
		mom.add(_offset, 'm')
		
		_year = mom.year();
		_month = mom.month();
		_day = mom.date();
		_hour = mom.hour();
		_minute = mom.minute();
		_view = View.make(_scope.template('calendar'));
		
		render(_year, _month);
		update();
	}
	
	
	var data = function(year, month)
	{
		var mom = moment(year + '-01-01 00:00').add(month, 'M');

		var start = mom.format('d') - 1;
		start = start == -1 ? 6 : start;
		
		var days = mom.endOf('month').format('D');
		var weeks = [];
		var week = [];
		
		for(var i = 0; i < start; i++){
			week.push(false);
		}
		for(var day = 1; day <= days; day++) {
			week.push(day);
			if(week.length == 7) {
				weeks.push(week);
				week = [];
			}
		}
		if(week.length > 0) {
			for(var i = week.length; i < 7; i++){
				week.push(false);
			}
			weeks.push(week);
		}
		
		// get weekday names, start with Mon
		var days = moment.weekdaysShort();
		days.push(days.shift());
		
		return {
			current: {
				year: _year,
				month: _month,
				day: _day,
				hour: _hour,
				minute: _minute,
			},
			year: year,
			month: mom.format('MMMM'),
			monthnumber: month,
			days: days,
			weeks: weeks,
			active: year == _year && month == _month ? _day : false,
			time: _time
		}
	}
	
	
	var render = function(year, month)
	{
		var calendar = _view.element(data(year, month), {
			previous: function () {
				month--;
				if(month == -1) {
					year--;
					month = 11;
				}
				render(year, month);
			},
			next: function () {
				month++;
				if(month == 12) {
					year++;
					month = 0;
				}
				render(year, month);
			},
			date: function (year, month, day) {
				_year = year;
				_month = month;
				_day = day;
				_scope.fetch('day').removeClass('badge-primary')
				_scope.fetch('day').addClass('badge-light')
				_scope.fetch('day-' + day).removeClass('badge-light')
				_scope.fetch('day-' + day).addClass('badge-primary')
				update();
			},
			hourup: function () {
				_hour++;
				if(_hour == 24) {
					_hour = 0;
				}
				update();
			},
			hourdown: function () {
				_hour--;
				if(_hour == -1) {
					_hour = 23;
				}
				update();
			},
			hourchange: function () {
				_hour = Number(_scope.fetch('hour').val().replace(/[^0-9]+/g, ''));
				update();
			},
			minuteup: function () {
				_minute++;
				if(_minute == 60) {
					_minute = 0;
				}
				update();
			},
			minutedown: function () {
				_minute--;
				if(_minute == -1) {
					_minute = 59;
				}
				update();
			},
			minutechange: function () {
				_minute = Number(_scope.fetch('minute').val().replace(/[^0-9]+/g, ''));
				update();
			},
		});
		_scope.fetch('container').empty().append(calendar);
	}
	
	var update = function()
	{
		// display correct computer time
		var mom = moment(_year + '-01-01 00:00')
		.add(_month , 'M')
		.add(_day - 1, 'd')
		.add(_hour, 'h')
		.add(_minute, 'm');

		if(_time) {
			_scope.fetch('date').val(mom.format('ll - H:mm'));
		} else {
			_scope.fetch('date').val(mom.format('ll'));
		}
		_scope.fetch('hour').val(mom.format('H'));
		_scope.fetch('minute').val(mom.format('mm'));
		
		// set value to server time: add or subtract timezone difference
		_value = moment(_year + '-01-01 00:00')
		// add the timezone difference in minutes
		.subtract(_offset,  'm')
		.add(_month , 'M')
		.add(_day - 1, 'd')
		.add(_hour, 'h')
		.add(_minute, 'm')
		.format('YYYY-MM-DD HH:mm');
	}
	

	this.value = function()
	{
		return _value; 
	}
});

//_____ manager/form/element/Textarea.js _____//

define('manager.form.element.Textarea')
.as(function(y) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		_scope.val(scope.data('value'));
	}
	
	this.value = function(value)
	{
		if(y.isSet(value)) {
			_scope.val(value)
		}
		return _scope.val();
	}
});

//_____ manager/form/element/Blocks.js _____//

define('manager.form.element.Blocks')
.use('yellow.View')
.as(function(y, View) {
	
	var _scope;
	var _value;
	var _max;
	var _autoselect;
	var _prototypes = {};

	this.start = function(scope) 
	{
		_scope = scope;
		
		_value = scope.data('value');
		if(! y.isArray(_value)) {
			_value = [];
		}
		
		_max = scope.data('max');
		_autoselect = scope.data('autoselect');
		
		// fetch prototypes
		scope.fetch('prototype').each(function(){
			_prototypes[y(this).data('type')] = y(this)
		});
		
		// add existing
		for(var i = 0; i < _value.length; i++ ) {
			add(_value[i].type, _value[i]);
		}
		
		// new
		scope.fetch('block-add').click(function(e){
			var block = add(y(this).data('type'), {});
			refresh();
			setTimeout(function(){
				// open editor
				edit(block);
				
				// automagically open the first relation selector
				if(_autoselect) {
					block.fetch('element').each(function(){
						var relation = y(this);
						var create = relation.fetch('create');
						if(create.length > 0) {
							create.click();
							return false
						}
						var create = relation.fetch('add');
						if(create.length > 0) {
							create.click();
							return false
						}
					});
				}
			}, 100);
		});


		// Click on document: check if the click was part of the active block.
		// If not: close all blocks by editing an empty block
		y(document).click(function(e){
			var keep = (
				// dont close on clicks inside the active editor
				y(e.target).fetch('block','closest').hasClass('editing')
				// dont close on all kinds of buttons outside of the editor
				|| y(e.target).hasClass('mce-text') 
				|| y(e.target).hasClass('mce-ico')
				|| y(e.target).hasClass('mce-active')
				|| y(e.target).hasClass('dialog-close')
				|| y(e.target).hasClass('overlay')
			);
			if(! keep) {
				edit(y('<div></div>'))
			}
		});
		
		refresh();
	}
	
	
	var add = function(type, data, before, after)
	{
		if(proto = _prototypes[type]) {
			
			// create entire block
			var block =  View.make(_scope.template('block')).element({
				name: proto.data('name'),
				type: type
			});
			
			if(before) {
				block.insertBefore(before);
			} else if(after) {
				block.insertAfter(after);
			} else {
				_scope.fetch('blocks').append(block);
			}

			
			// make sortable
			_scope.fetch('blocks').sortable({
				stop: function(evt, ui){
					// add a new one
					add(ui.item.data('type'), value(ui.item), ui.item);
					// remove this one
					ui.item.remove();
					refresh();
				}
			});

			// delete button
			block.fetch('block-delete').click(function(e){
				e.stopPropagation();
				block.remove();
				refresh();
			});

			// update button
			block.fetch('block-update').click(function(e){
				e.stopPropagation();
				edit(block)
			});
		
			// up button
			block.fetch('block-up').click(function(e){
				// add a new one
				add(type, value(block), block.prev());
				// remove this one
				block.remove();
				refresh();
			});


			// down button
			block.fetch('block-down').click(function(e){
				e.stopPropagation();
				// add a new one
				add(type, value(block), null, block.next());
				// remove this one
				block.remove();
				refresh();
			});


			// create the editor
			var editor = proto.make();
			block.append(editor);
			
			// set values
			editor.fetch('element').each(function(){
				var element = y(this);
				var key = element.data('key');
				if(y.isSet(data[key])) {
					element.data('value', data[key])
				}
			});
			editor.show();
			editor.start();
			editor.attr('y-name', 'editor');
			render(block);
		
			block.click(function(e){
				if(! block.hasClass('editing')) {
					e.stopPropagation();
					edit(block);
				}
			});
			
			return block;
		}
	}
	

	var edit = function(block)
	{
		// render all blocks if editor is visible
		_scope.fetch('block').each(function(){
			if(y(this).fetch('editor').is(':visible')) {
				render(y(this));
			}
		});
		
		// remove this render
		block.fetch('render').remove();
		
		// activate
		block.addClass('editing');
		
		// show this editor
		block.fetch('editor').show();
	}
	
	
	var render = function(block)
	{
		// remove old render
		block.fetch('render').remove();
		
		// deactivate
		block.removeClass('editing');
		
		// hide editor
		block.fetch('editor').hide();
		
		// create render
		var _render = View.make(block.template('render')).element(value(block));
		_render.attr('y-name', 'render');
		_render.addClass('clickable');
		block.append(_render);
	}
	
	
	var value = function(block)
	{
		// get values
		var data = {
			type: block.data('type')
		};
		
		block.fetch('element').each(function(){
			var element = y(this);
			var key = element.data('key');
			data[key] = element.invoke('value');
		});
					
		// add full relation data
		block.fetch('relation').each(function(){
			var relation = y(this);
			var key = relation.data('key');
			data[key] = relation.data('value');
		});
		
		return data;
	}
	

	this.value = function()
	{
		var values = [];
		_scope.fetch('block').each(function(){
			var data = value(y(this));
			data.type = y(this).data('type');
			values.push(data);
		});
		return values;
	}



	var refresh = function()
	{
		// get the current relations
		var blocks = _scope.fetch('block');
		
		// show / hide add button
		if(blocks.length >= _max) {
			_scope.fetch('blocks-add').hide();
		} else {
			_scope.fetch('blocks-add').show();
		}
		
		blocks.each(function(){
			var block = y(this);
			var first = block.is(':first-child');
			var last = block.is(':last-child');
			if(first && last) {
				block.fetch('up').hide();
				block.fetch('down').hide();
			} else if(first) {
				block.fetch('up').hide();
				block.fetch('down').show();
			} else if(last) {
				block.fetch('up').show();
				block.fetch('down').hide();
			} else {
				block.fetch('up').show();
				block.fetch('down').show();
			}
		});
	}
});

//_____ manager/form/element/Toggle.js _____//

define('manager.form.element.Toggle')
.as(function(y) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		if (scope.data('value') == 1) {
			_scope.find('input[type=checkbox]').attr('checked', 'checked');
		}
	}
	
	this.value = function()
	{
		if(_scope.find('input[type=checkbox]:checked').length > 0) {
			return 1;
		} else {
			return 0;
		}
	}
});

//_____ manager/form/element/Relation.js _____//

define('manager.form.element.Relation')
.use('yellow.View')
.use('manager.Dialog')
.use('manager.Callback')
.as(function(y, View, Dialog, Callback) {
	
	var _scope;
	
	var _value;
	
	var _multiple;
	
	var _max;
	
	var _order;
	
	var _current = null;
	
	this.start = function(scope) 
	{
		
		_scope = scope;
		
		_value = scope.data('value');
		
		_multiple = scope.data('multiple');
		
		_max = _multiple ? scope.data('max') : 0;
		
		_order = scope.data('order') == 'desc' ? 'desc' : 'asc';
		
		_scope.fetch('add').click(function(e){
			var dialog = Dialog.iframe(scope.data('url_select').replace('{{callback}}', Callback.register(function(data) {
				dialog.remove();
				update(data);
				refresh();
			})))
		});
		
		if(_order === 'desc') {
			_scope.fetch('container').before(_scope.fetch('add'));
		}
		
		if(_multiple) {
			var relatives = y.isArray(_value) ? _value : [];
		} else {
			var relatives = y.isObject(_value) ? [ _value ] : [];
		}
		
		if(_order === 'desc') {
			relatives.reverse();
		}
		
		for(var i = 0; i < relatives.length; i++ ) {
			update(relatives[i]);
		}
		
		refresh();
	}
	
	
	this.value = function()
	{
		return _value;
	}
	
	
	/**
	 * Incoming data from the dialog
	 * @param {type} data
	 * @returns {undefined}
	 */
	var update = function(data)
	{
		// make sure junction is set
		data.junction = data.junction || {};
		
		// create a relation element
		var relative = View.make(_scope.template('relative')).element(data);
		
		// set the data
		relative.data('data', data);

		// update values when changing junction vals
		relative.fetch('junction').change(refresh);

		// update button
		if(_scope.data('update')) {
			relative.fetch('update').click(function(e){
				var self = y(this);
				var relative =  self.fetch('relative', 'closest');
				var id = relative.data('id');
				var dialog = Dialog.iframe(_scope.data('url_update').replace('{{id}}', id).replace('{{callback}}', Callback.register(function(data) {
					dialog.remove();
					_current = relative;
					update(data);
					refresh();
				})))
			});
		} else {
			relative.fetch('update').hide();
		}
		
		// delete button
		relative.fetch('delete').click(function(e){
			y(this).fetch('relative', 'closest').remove();
			refresh();
		});
		
		// append element, or replace existing, when editing
		if(_current) {
			// insert the relation after the original relation
			_current.after(relative);
			// move the original junction part to the newly inserted relation
			// and remove the new (empty) junction part
			var relativeJunction = relative.fetch('junction');
			relativeJunction.after(_current.fetch('junction'));
			relativeJunction.remove();
			// remove the now obsolete original relation
			_current.remove();
			_current = null;
		} else {
			if(_order === 'desc') {
				_scope.fetch('container').prepend(relative);
			} else {
				_scope.fetch('container').append(relative);
			}
		}
	}
	
	
	var refresh = function()
	{
		// get the current relations
		var relatives = _scope.fetch('relative');
		
		
		// show / hide add button
		if( (_multiple && relatives.length >= _max) || (! _multiple && relatives.length >= 1) ) {
			_scope.fetch('add').hide();
		} else {
			_scope.fetch('add').show();
		}
		
		// get the data in full and for the serverside
		var full = [];
		var value = [];
		relatives.each(function(){
			var relative = y(this);
			var data = relative.data('data');
			data.junction = {};
			relative.fetch('junction').each(function(){
				var junction = y(this);
				data.junction[junction.data('name')] = junction.val();
			});
			full.push(data);
			
			var item = data.junction;
			item.id = data.id;
			value.push(item);
		});
			
		if (! _multiple) {
			if(full.length > 0) {
				// single relation: only use the first
				full = full[0];
				value = value[0];
			} else {
				full = null
				value = 0
			}
		} 
		
		// set entire dataset for other purposes
		_scope.data('value', full);
		
		// save value as the internal value
		_value = value;
		
	
		// make sortable
		if(_multiple && _max > 1) {
			_scope.fetch('relative').addClass('movable');
			_scope.fetch('container').sortable({
				items: '[y-name^=relative]', 
				containment: _scope,
				tolerance: 'pointer',
				placeholder: 'placeholder',
				stop: refresh,
			});
		}
	}
});

//_____ manager/form/element/Image.js _____//

define('manager.form.element.Image')
.use('yellow.View')
.use('manager.Dialog')
.use('manager.Callback')
.use('manager.Message')
.as(function(y, View, Dialog, Callback, Message, self)
{
	var _scope;
	
	var _value;
	
	var _multiple;
	
	var _max;
	
	var _dropzone;
	
	var _current = null;

	this.start = function(scope)
	{
		_scope = scope;
			
		_value = scope.data('value');

		_multiple = scope.data('multiple');
		
		_max = _multiple ? scope.data('max') : 1;
		
		_dropzone = new Dropzone(scope.fetch('zone')[0] , {
			maxFiles: _max,
			url: _scope.data('url_create'),
			success: function(file, response) {
				this.removeFile(file);
				response = JSON.parse(response);
				if(response.success && y.isSet(response.items[0])) {
					update(response.items[0].data);
				}
				if(! response.success && response.errors.length > 0) {
					Message.make(response.errors[0], 'error');
				}
				refresh();
			},
			init: function() {
				this.on('addedfile', function(file){
					if(_scope.fetch('image').length + this.getQueuedFiles().length >= _max) {
						this.removeFile(file);
					}
				});
			}
		});

		
		if(_multiple) {
			var images = y.isArray(_value) ? _value : [];
		} else {
			var images = y.isObject(_value) && _value.id && _value != '0' ? [ _value ] : [];
		}
		
	
		for(var i = 0; i < images.length; i++ ) {
			update(images[i]);
		}
		refresh();
	}
	
	
	this.value = function()
	{
		return _value;
	}
	
	
	/**
	 * Incoming data from upload
	 * @param {type} data
	 * @returns {undefined}
	 */
	var update = function(data)
	{
		// make sure junction is set
		data.junction = data.junction || {};
		
		// create a relation element
		var image = View.make(_scope.template('image')).element(data);
		
		// set the data
		image.data('data', data);

		// update values when changing junction vals
		image.fetch('junction').change(refresh);


		// Crop button
		image.fetch('crop').click(function(e){
			var dialog;
			var callback = Callback.register(function(data) {
				dialog.remove();
				var img = image.fetch('img');
				var src = img.attr('src');
				var glue = '?';
				if(src.indexOf('?') > -1) {
					glue = '&';
				}
				img.attr('src', src + glue + new Date().getTime());
			});
			dialog = Dialog.iframe(_scope.data('url_crop').replace('{{id}}', data.id).replace('{{callback}}', callback));
		});

		// Delete button
		image.fetch('delete').click(function(e){
			var dialog = Dialog.make({
				title: _scope.data('title'),
				body: _scope.data('message'),
				close: true,
				width: 600,
				height: 300,
				buttons: [
					{type: 'primary', label: _scope.data('instance'), action: function(){
						image.remove();
						refresh();
						dialog.remove(); 
					}},
					{type: 'secondary', label: _scope.data('original'), action: function(){
						image.remove();
						y.ajax(_scope.data('url_delete').replace('{{id}}', data.id), {
							type : 'POST',
							data: {
								csrf: _scope.data('csrf')
							}
						})
						refresh();
						dialog.remove()
					}},
				]
			});
			return dialog;
		});
		
		_scope.fetch('container').append(image);
	}
	
	
	var refresh = function()
	{
		// get the current images
		var images = _scope.fetch('image');
		
		// show / hide add button
		if( (_multiple && images.length >= _max) || (! _multiple && images.length >= 1) ) {
			_scope.fetch('zone').hide();
		} else {
			_scope.fetch('zone').show();
		}
		
		// get the data in full and for the serverside
		var full = [];
		var value = [];
		
		images.each(function(){
			var image = y(this);
			var data = image.data('data');
			data.junction = {};
			image.fetch('junction').each(function(){
				var junction = y(this);
				data.junction[junction.data('name')] = junction.val();
			});
			full.push(data);
			
			var item = data.junction;
			item.id = data.id;
			value.push(item);
		});
			
			
		if (! _multiple) {
			if(full.length > 0) {
				// single relation: only use the first
				full = full[0];
				value = value[0];
			} else {
				full = null
				value = 0
			}
		} 
		
		// set entire dataset for other purposes
		_scope.data('value', full);
	
		// save value as the internal value
		_value = value;

	
		// make sortable
		if(_multiple && _max > 1) {
			_scope.fetch('image').addClass('movable');
			_scope.fetch('container').sortable({
				items: '[y-name^=image]', 
				containment: _scope,
				tolerance: 'pointer',
				placeholder: 'placeholder',
				stop: refresh,
			});
		}
	}
});

//_____ manager/Message.js _____//

define('manager.Message')
		
.use('yellow.View')

.template('message', '\
<div class="flashmessage">\
	<div class="alert alert-{{ type }}" role="alert">\
		{{ message }}\
		<button type="button" class="close" data-dismiss="alert">\
		  <span aria-hidden="true">&times;</span>\
		  <span class="sr-only">Close</span>\
		</button>\
	</div>\
</div>\
')
.as(function(y, View, template)
{
	this.start = function(message, type)
	{
		var types = {
			success: 'success',
			error: 'danger'
		};
		
		var message = View.make(template('message')).element({
			message: message,
			type: types[type]
		}).appendTo('body');
		
		
		setTimeout(function(){
			message.fadeOut(function(){
				this.remove();
			});
		}, 4000);
	}
});

//_____ manager/Callback.js _____//

define('manager.Callback')
.set({
	_instance: null,
	instance: function() 
	{
		if(this._instance === null) {
			this._instance = this.make()
		}
		return this._instance;
	},
	
	register: function(fn)
	{
		return this.instance().register(fn);
	},
	
	invoke: function(id, data)
	{
		return this.instance().invoke(id, data);
	},
})
.as(function(y, self){
	
	var _callbacks;
	
	this.start = function(globalName)
	{
		globalName = globalName || '__callbacks__';
		var outer = y.outer();
		outer[globalName] = outer[globalName] || {};
		_callbacks = outer[globalName];
	};
	
	this.register = function(fn) {
		var id = '__' + new Date().getTime() + Math.round(Math.random() * 100000);
		_callbacks[id] = fn;
		return id;
	};
	
	this.invoke = function(id, data)
	{
		if(y.isSet(_callbacks[id])){
			return _callbacks[id](data);
		}
		return null;
	};
});

//_____ manager/form/element/Tinymce.js _____//

define('manager.form.element.Tinymce')
.use('yellow.View')
.use('manager.Callback')
.use('manager.Dialog')
.template('link',
'<a href="{{ url }}" data-mce-href="{{ url }}"{% if blank== 1%} target="_blank"{% endif %}{% if nofollow == 1%} rel="nofollow"{% endif %} title="{{ title }}">{{{ body }}}</a>'
)
.template('image',
'<img align="{{ align }}" src="{{ src }}" alt="{{ title }}"/>'
).set({
	started: false,
})

.as(function(y, View, Callback, Dialog, Template, self)
{
	var _scope;
	
	/**
	 * init
	 */
	this.start = function(scope)
	{
		if(! self.started) {
			// add custom link plugin
			tinymce.PluginManager.add('link', function(editor) {
				editor.addButton('link', {
					text: false,
					icon: 'link',
					stateSelector: 'a[href]',
					onclick: function(){
						link(editor)
					}
				});
			});
			
			tinymce.PluginManager.add('unlink', function(editor) {
				editor.addButton('unlink', {
					text: false,
					icon: 'unlink',
					stateSelector: 'a[href]',
					onclick: function(){
						unlink(editor)
					}
				});
			});
		
			self.started = true;
		}
		
		
		_scope = scope;
		
		_scope.fetch('textarea').val(scope.data('value'));
		
		
		// get config
		var config = scope.data('config');
		
		// wait until loaded
		config.init_instance_callback = function(editor){
			// capture click on image button
			scope.find('.mce-i-image').closest('.mce-widget').click(function(e){
				var node = editor.selection.getNode();
				if(node.nodeName != 'IMG' && node.nodeName != 'FIGURE' && node.nodeName != 'FIGCAPTION') {
					image(editor);
					// dont call original image
					e.stopPropagation();
				}
			});
		}
		
		//var id = '__' + new Date().getTime() + Math.round(1000000 * Math.random());
		
		//scope.fetch('textarea').attr('id', id)
		
		config.target = scope.fetch('textarea')[0];
		
		// init tiny
		tinymce.init(config);
	};
	
	
	this.value = function()
	{
		// manually trigger tiny save
		tinymce.triggerSave();
		return _scope.find('textarea').val();
	}
	

	var link = function(editor)
	{
		// data for the dialog
		var data = null;
		
		// get selected node
		var node = y(editor.selection.getNode()).closest('a');
		
		// fill up data with node
		if(node.length > 0){
			data = {
				url: node.attr('href'),
				title: node.text(),
				blank: node.attr('target') == '_blank' ? 1 : 0,
				nofollow:  node.attr('rel') == 'nofollow' ? 1 : 0
			}
   		} else {
			data = {
				title: editor.selection.getContent({format : 'text'})
			}
		}
		
		var dialog;
		var callback = Callback.register(function(data) {
			dialog.remove();
			
			if(node.length == 0){
				var template = _scope.template('link');
				if(! template) {
					template = Template('link');
				}
				// new node
				var content = editor.selection.getContent();
				if(content) {
					data.body = content
				} else {
					data.body = data.title
				}
				editor.selection.setContent(View.make(template).render(data));
			} else {
				// existing node
				node.attr('href',data.url);
				node.attr('data-mce-href', data.url);
				node.attr('title', data.title);
				node.text(data.title);
				if(data.blank == 1) {
					node.attr('target', '_blank');
				} else {
					node.removeAttr('target');
				}
				if(data.nofollow == 1) {
					node.attr('rel', 'nofollow');
				} else {
					node.removeAttr('rel');
				}
			}
		});
		dialog = Dialog.iframe(_scope.data('url_link').replace('{{callback}}', callback), data);
	};


	var unlink = function(editor)
	{
		// get selected text
		var selected = editor.selection.getContent({format : 'text'});
		
		// get selected a node
		var node = y(editor.selection.getNode()).closest('a');
		
		if(node.length > 0) {
			// get linked html
			var linked = node.html();


			if(selected == '') {
				// no text was selected: remove link
				node.replaceWith(linked);
			} else {
				// split on selected text
				var parts = linked.split(selected);
				if(parts.length > 2) {
					// multiple occurences of selected: too hard: just remove link
					node.replaceWith(linked);
				} else if (parts.length < 2) {
					// selected not found (impossible): just remove link
					node.replaceWith(linked);
				} else if(parts[0] == '' && parts[1] == '') {
					// linked = selection : just remove link
					node.replaceWith(linked);
				} else if(parts[0] == '') {
					// selected was at the front
					// put back the remaining linked html and before that the selected text
					node.html(parts[1]).before(selected)
				} else if(parts[1] == '') {
					// selected was at the end
					// put back the remaining linked html and after that the selected text
					node.html(parts[0]).after(selected)
				} else {
					// selected was in the middle: just remove link
					node.replaceWith(linked);
				}
			}
		}
	}


	var image = function(editor)
	{
		var dialog;
		var callback = Callback.register(function(data) {
			dialog.remove();
			var template = _scope.template('image');
			if(! template) {
				template = Template('image');
			}
			editor.selection.setContent(View.make(template).render(data));
		});
		dialog = Dialog.iframe(_scope.data('url_image').replace('{{callback}}', callback));
	};
});


//_____ manager/form/Crop.js _____//

define('manager.form.Crop')
.use('manager.Callback')
.as(function(y, Callback) {
	
	this.start = function(scope) 
	{
		var cropper = new Cropper(scope.fetch('image')[0], {
			viewMode: 1,
			//background: false
		});
		
		// https://github.com/fengyuanchen/cropperjs/blob/master/README.md
		
		scope.fetch('ratio').click(function(){
			var ratio = y(this).data('ratio');
			//if(ratio == 0){
				//cropper.clear();
			//} else {
				cropper.setAspectRatio(y(this).data('ratio'));
			//}
		});
		
		scope.fetch('submit').click(function(){
			y.ajax(scope.data('url'), {
				method: 'POST',
				data: cropper.getData(true),
				dataType: 'JSON'
			}).done(function(data) {
				if(data.success) {
					Callback.invoke(scope.data('callback'));
				}
			});
		});
	}
});

//_____ manager/index/Toggle.js _____//

define('manager.index.Toggle')
.as(function(y) {
	this.start = function(scope) 
	{
		scope.fetch('state').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			scope.fetch('state').show();
			y(this).hide();
			
			var url = scope.data('url').replace('{{status}}', y(this).data('status'));
			// TODO: csrf
			y.ajax(url, {
				dataType: 'json',
				type : 'POST',
				data: {
					csrf: scope.data('csrf')
				}
			});
			return false;
		});
		
		scope.fetch(scope.data('value')).show();
	}
});

//_____ manager/Date.js _____//

define('manager.Date')
//.use('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js', 'moment')
//.use('moment.Moment', 'moment')
.as(function(y) {
	
	this.start = function(scope) 
	{
		if(scope.data('date')) {
			var offset = scope.data('offset') || 0;
			moment.locale(scope.data('lang'));
			scope.text(
				moment(scope.data('date'))
				.add(offset, 'm')
				.format(scope.data('format'))
			);
		}
	}
});

//_____ manager/index/Select.js _____//

define('manager.index.Select')
.use('manager.Callback')
.as(function(y, Callback) {
	this.start = function(scope) 
	{
		scope.click(function(e){
			e.preventDefault();
			e.stopPropagation();
			Callback.invoke(scope.data('callback'), scope.fetch('item', 'closest').data('item'));
		});
	}
});

//_____ manager/index/Delete.js _____//

define('manager.index.Delete')
.use('manager.Loading')
.use('manager.Dialog')
.as(function(y, Loading, Dialog) {
	this.start = function(scope) 
	{
		scope.click(function(e){
			e.preventDefault();
			e.stopPropagation();
			var confirm = Dialog.confirm(scope.data('title'), scope.data('message'), function(){
				confirm.remove();
				Loading.show();
				y.ajax(scope.data('href'), {
					dataType: 'json',
					type : 'POST',
					data: {
						csrf: scope.data('csrf')
					}
				})
				.always(function(){
					Loading.hide();
				})
				.done(function(data){
					if(data.success) {
						// remove the item
						scope.fetch('item', 'closest').remove();
					} else {
						Dialog.alert(data.message);
					}
				});
			});
		});
	}
});

//_____ manager/form/element/Upload.js _____//

define('manager.form.element.Upload')
.use('yellow.View')
.use('manager.Message')
.as(function(y, View, Message, self)
{
	var _scope;

	this.start = function(scope)
	{
		_scope = scope;
		
		var id = 'id_' + new Date().getTime();
		var el = View.make(scope.template('zone')).element({id: id});
		scope.append(el);
		var dropzone = new Dropzone('div#' + id , {
			url: scope.data('url'),
			success: function(file, response) {
				file.previewElement.remove();
				response = JSON.parse(response);
				if(response.success && y.isSet(response.items[0])) {
					y('body').fetch('index').invoke('add', response.items, true);
				}
				if(! response.success && response.errors.length > 0) {
					Message.make(response.errors[0], 'error');
				}
			}
		});
	}
});

//_____ manager/index/Crop.js _____//

define('manager.index.Crop')
.use('manager.Callback')
.use('manager.Dialog')
.as(function(y, Callback, Dialog) {
	this.start = function(scope) 
	{
		scope.click(function(e){
			var dialog;
			var callback = Callback.register(function(data) {
				dialog.remove();
				var img = scope.fetch('item', 'closest').fetch('image');
				var src = img.attr('src');
				var glue = '?';
				if(src.indexOf('?') > -1) {
					glue = '&';
				}
				img.attr('src', src + glue + new Date().getTime());
			});
			dialog = Dialog.iframe(scope.data('url').replace('{{callback}}', callback));
		});
	}
});

//_____ manager/form/element/Redirect.js _____//

define('manager.form.element.Redirect')
.use('yellow.View')
.as(function(y, View) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		
		
		var value = scope.data('value');
		
		if(! y.isObject(value)) {
			value = {};
		}

		for(var i in value) {
			add(i, value[i]);
		}
		
		// add empty element
		scope.fetch('add').click(function(){
			add('','');
		}).click();
	}
	
	
	var add = function(from, to)
	{
		var element = View.make(_scope.template('redirect')).element({});
		element.fetch('from').val(from);
		element.fetch('to').val(to);
		_scope.fetch('container').append(element);
		element.fetch('delete').click(function(){
			element.remove();
		});
	}
	
	
	this.value = function()
	{
		var value = {};
		_scope.fetch('pair').each(function(){
			var from = y(this).fetch('from').val().replace(/^\/+|\/+$/g, '');
			var to = y(this).fetch('to').val().replace(/^\/+|\/+$/g, '');
			if(from || to) {
				value[from] = to;
			}
		});
		return value;
	}
});

//_____ manager/form/element/Link.js _____//

define('manager.form.element.Link')
.use('yellow.View')
.use('manager.Dialog')
.use('manager.Callback')
.as(function(y, View, Dialog, Callback) {
	
	var _scope;
	var _data;
	
	this.start = function(scope) 
	{
		_scope = scope;
		
		_data = scope.data('value');
		if(! y.isObject(_data)) {
			_data = {};
		}
	
		scope.fetch('create').click(function(){
			dialog(null);
		});
		

		if(_data && y.isSet(_data.url)) {
			update(_data);
		} else {
			refresh();
		}
	}
	
	this.value = function()
	{
		return _data;
	}
	
	var dialog = function(value)
	{
		var dialog;
		var callback = Callback.register(function(data) {
			dialog.remove();
			update(data);
		});
		dialog = Dialog.iframe(_scope.data('url').replace('{{callback}}', callback), value);
	}
	
	
	var update = function(data)
	{
		// save the data 
		_data = data;
		
		// render the data, by making a copy
		var json = JSON.stringify(data);
		var data = JSON.parse(json);
		
		// add the json itself
		data.value = json.replace(/\"/g, '\"');
		
		var link = View.make(_scope.template('link')).element(data);
		
		_scope.fetch('container')
		.empty()
		.append(link);

		link.fetch('update').click(function(){
			dialog(_data);
		});
		
		link.fetch('delete').click(function(){
			remove();
		});
		
		// broadcast change, so a form can pick it up
		_scope.data('title', data.title || '')
		.change();

		refresh();
	}
	
	
	var remove = function()
	{
		_scope.fetch('container').empty();
		_data = null;
		refresh();
	}
	
	
	
	var refresh = function()
	{
		if(_data && y.isSet(_data.url)) {
			_scope.fetch('create').hide();
		} else {
			_scope.fetch('create').show();
		}
	}
});

//_____ manager/form/element/Tag.js _____//

define('manager.form.element.Tag')
.use('yellow.View')
.as(function(y, View) {
	

	var _scope;

	this.start = function(scope)
	{
		_scope = scope;
		var value = scope.data('value');
		if(! y.isArray(value)) {
			value = [];
		}


		// get popular tags
		y.ajax(scope.data('url_popular'), {
			dataType: 'JSON'
		}).done(function(data) {
			for(var i = 0; i < data.items.length; i++) {
				var item = data.items[i].data;
				var suggestion = View.make(_scope.template('suggestion')).element(item);
				suggestion.data('item', item);
				scope.fetch('popular').append(suggestion);
				suggestion.click(function(e){
					add(y(this).data('item'));
					scope.fetch('input').val('');
				});
			}
		});


		// add initial tags
		for(var i = 0; i < value.length; i++) {
			add(value[i]);
		}
		
		
		// check val, show/hide add
		setInterval(function(){
			var input = scope.fetch('input');

			if(input.val() !== '') {
				scope.fetch('add').show();
			} else {
				scope.fetch('add').hide();
			}
		}, 200);


		// add button
		scope.fetch('add').click(function(){
			var input = scope.fetch('input');
			if(input.val() !== '') {
				create(input.val());
				input.val('');
				scope.fetch('add').hide();
			}
		});
		
		
		// keyboard events on textfield
		var timeout;
		scope.fetch('input').keyup(function(e){
			var input = y(this);
			
			// catch enter or comma
			if(e.keyCode == '13' || e.keyCode == '188') {
				// don't submit form by accident
				if(e.keyCode == '13') {
					e.preventDefault();
				}
				var val = input.val().trim().replace(/\,/g, '');
				if(val != ''){
					create(val);
					input.val('');
					scope.fetch('suggestions').hide()
				}
            }
			
			// clear existing timeout fo suggestions
			clearTimeout(timeout);
			if(input.val().length > 0){
				// create new interval
				timeout = setTimeout(function(){
					// fire an ajax call to get suggestions
					y.ajax(scope.data('url_search').replace('{{query}}',encodeURIComponent(input.val())), {
						dataType:'json'
					}).done(function(data){
						var suggestions = scope.fetch('suggestions')
						suggestions.empty();
						if(data.items.length > 0) {
							scope.fetch('suggestions').show();
							for(var i = 0; i < data.items.length; i++) {
								var item = data.items[i].data;
								var suggestion = View.make(_scope.template('suggestion')).element(item);
								suggestion.data('item', item);
								suggestions.append(suggestion);
								suggestion.click(function(e){
									add(y(this).data('item'));
									scope.fetch('input').val('');
									scope.fetch('suggestions').empty();
									scope.fetch('suggestions').hide();
								});
								y('body').click(function(){
									suggestions.hide();
								})
							}
						} else {
							scope.fetch('suggestions').hide();
						}
					});
				},500);
			} else {
				scope.fetch('suggestions').hide();
			}
		}).keyup();
	}
	
	
	this.value = function() {
		var val = [];
		_scope.fetch('tag').each(function(){
			val.push(y(this).data('id'))
		});
		return val;
	}
	
	
	/**
	 * Create a tag
	 */	
	var create = function(title)
	{
		var tag;
		if(tag = add({
			id: 0,
			title: title,
		})) {
			y.ajax(_scope.data('url_create'), {
				dataType: 'json',
				method: 'POST',
				data: {
					title: title,
					csrf: _scope.data('csrf')
				}
			}).done(function(data){
				tag.data('id', data.id);
			});
		}
	}
	
	
	
	/**
	 * add a tag
	 */
	var add = function(item)
	{
		var unique = true;
		_scope.fetch('tag').each(function(){
			if(item.title == y(this).data('title')) {
				unique = false;
			}
		});
		
		if (! unique) {
			return false;
		}
		
		var tag = View.make(_scope.template('tag')).element(item);
		tag.data('id', item.id);
		tag.data('title', item.title);
	
		tag.appendTo(_scope.fetch('tags'));
		
		tag.fetch('remove').click(function(){
			tag.remove();
		});
		
		return tag;
	}
});

//_____ manager/form/element/Seo.js _____//

define('manager.form.element.Seo')
.use('yellow.View')
.as(function(y, View)
{
	var _scope;
	
	var _title;
	var _description;
	var _keyword;
	var _value;
	
	var _website;
	var _base;
	var _locale;
	var _lang;
	var _readability;
	var _findability;
	
	var _sourceTitle;
	var _sourceSlug;
	var _sourceBody;
	
	var _snippet;
	var _bar;
	var _content;
	var _seo;
	
	var _viewSnippet;
	var _viewBar;
	var _viewResult;

	this.start = function(scope)
	{
		_scope = scope;
		
		// cache internal elements
		_title = scope.fetch('title');
		_description = scope.fetch('description');
		_keyword = scope.fetch('keyword');

		// set form values
		_value = scope.data('value') || {};
		
		scope.fetch('title').val(_value.title || '');
		scope.fetch('description').val(_value.description || '');
		scope.fetch('keyword').val(_value.keyword || '');
		
		// cache data
		_website = scope.data('website');
		_base = scope.data('base');
		_locale = scope.data('locale') || 'en_US';
		_lang = scope.data('lang') || null;
		_readability = scope.data('readability') ;
		_findability = scope.data('findability') ;
		
		// cache  source elements
		_sourceTitle = scope.closest('form').fetch('element-' + scope.data('source_title'));
		_sourceSlug = scope.closest('form').fetch('element-' + scope.data('source_slug'));

		// cache body source
		_sourceBody = [];
		var sourceBody = scope.data('source_body');
		for(var i = 0; i < sourceBody.length; i++) {
			_sourceBody.push(scope.closest('form').fetch('element-' + sourceBody[i]));
		}


		// cache containers
		_snippet = scope.fetch('snippet');
		_bar = scope.fetch('bar');
		_content = scope.fetch('result-content');
		_seo = scope.fetch('result-seo');
		
		// cache views
		_viewSnippet = View.make(scope.template('snippet'));
		_viewBar = View.make(scope.template('bar'));
		_viewResult = View.make(scope.template('result'));
		
		
		// open the advanced options
		scope.fetch('more').click(function(){
			//load the big script and then do the rest
			var parent = document.getElementsByTagName('body')[0];
			var script = document.createElement('script');
			script.src = scope.data('script');
			script.setAttribute('async', false);
			
			// add callback
			if(script.addEventListener){
				script.onload = start;
			} else if(script.readyState) {
				script.onreadystatechange = function(){
					if(script.readyState == 'loaded' || script.readyState == 'complete'){
						start();
					}
				};
			}
			// add script to the page to start loading
			document.getElementsByTagName('body')[0].appendChild(script);
		});


		// render preview
		var snippet = _viewSnippet.element({
			title: _title.val().replace(/\[title\]/g, _sourceTitle.val()).replace(/\[website\]/g, _website),
			base: _base,
			slug: _sourceSlug.val(),
			description: _description.val()
		});
		var snippetTitle = snippet.find('.yf-google-title');
		_snippet.empty().append(snippet);
		
		// render overall
		_bar.empty().append(_viewBar.element({
			percent: _value.score ? 10 * _value.score :  1
		}));
		
	}
	
	var start = function()
	{
		_scope.fetch('advanced').show();
		_scope.fetch('more').hide();
		var interval = setInterval(update, 500);
	}
	
	
	var update = function()
	{
		var snippet = _viewSnippet.element({
			title: _title.val().replace(/\[title\]/g, _sourceTitle.val()).replace(/\[website\]/g, _website),
			base: _base,
			slug: _sourceSlug.val(),
			description: _description.val()
		});
		var snippetTitle = snippet.find('.yf-google-title');
		_snippet.empty().append(snippet);
		
		var body = '';
		for(var i = 0; i < _sourceBody.length; i++) {
			var render = _sourceBody[i].fetch('render');
			if(render.length > 0 ) {
				var val = '';
				render.each(function(){
					val += y(this).html();
				})
			} else if(_sourceBody[i].fetch('image').length > 0) {
				var val = '<img src="source" alt="' + _keyword.val() + '" title="title" />';
			} else {
				var val = _sourceBody[i].invoke('value');
			}
			
			if(! y.isString(val)) {
				val = JSON.stringify(val);
			}
			body += '<p>' + val + '</p>';
		};
		
	
		// Do assessment
		var assessor = yoast.assessor(_lang && _lang != 'en'  ? lang[_lang] : null);
		var result = assessor.assess(body, {
			keyword: _keyword.val(),
			synonyms: "",
			description: _description.val(),
			title: snippetTitle.text(),
			titleWidth: snippetTitle.width(),
			url: _sourceSlug.val(),
			permalink: _base + _sourceSlug.val(),
			locale: _locale
		});

		// Content result
		result.content.sort(function(a, b){
			return a.score > b.score ? 1 : -1;
		});
		
		var sum = 0;
		var total = result.content.length;
		for(var i = 0; i < total; i++) {
			sum += result.content[i].score;
		}
		var avgContent = total > 0 ? Math.round(sum / total) : 1;
		_content.empty().append(_viewResult.element({
			label: _readability,
			score: avgContent,
			results: result.content
		}));
		
		
		// Seo result
		result.seo.sort(function(a, b){
			return a.score > b.score ? 1 : -1;
		});
		
		var sum = 0;
		var total = result.seo.length;
		for(var i = 0; i < total; i++) {
			sum += result.seo[i].score;
		}
		var avgSeo = total > 0 ? Math.round(sum / total) : 1;
		_seo.empty().append(_viewResult.element({
			label: _findability,
			score: avgSeo,
			results: result.seo
		}));
		
		// Overall bar
		_bar.empty().append(_viewBar.element({
			percent: Math.max(1, 10 * ((avgContent + avgSeo) / 2))
		}));
		
		// value
		_value = {
			title: _title.val(),
			description: _description.val(),
			keyword: _keyword.val(),
			score: (avgContent + avgSeo) / 2
		};
	}
	
	
	this.value = function() {
		return _value;
	}


	var lang = {
		nl: {
			"":{},
			"You have far too little content, please add some content to enable a good analysis.":[
				"Je hebt veel te weinig content, voeg wat content toe om een goede analyse te kunnen maken"
			],

			"%1$s of the sentences contain %2$spassive voice%3$s, which is less than or equal to the recommended maximum of %4$s.":[
				"%1$s van de zinnen bevat %2$spassieve toon%3$s, wat minder of gelijk is aan het aanbevolen maximum van %4$s."
			],
			"%1$s of the sentences contain %2$spassive voice%3$s, which is more than the recommended maximum of %4$s. Try to use their active counterparts.":[
				"%1$s van de zinnen bevat %2$spassieve toon%3$s, wat meer is dan het aanbevolen maximum van %4$s., Probeer actieve toon te gebruiken."
			],
			"The text contains %2$d consecutive sentences starting with the same word. Try to mix things up!":[
				"De tekst bevat %2$d opeenvolgende zinnen die beginnen met hetzelfde woord. Probeer af te wisselen.",
				"De tekst bevat %1$d gevallen waar %2$d of meer opeenvolgende zinnen beginnen met hetzelfde woord. Probeer af te wisselen."
			],
			"%1$s of the sentences contain a %2$stransition word%3$s or phrase, which is less than the recommended minimum of %4$s.":[
				"%1$s van de zinnen bevat een %2$sovergangswoord%3$s of zin, wat minder is dan het aanbevolen minimum van %4$s."
			],
			"%1$s of the sentences contain a %2$stransition word%3$s or phrase, which is great.":[
				"%1$s van de zinnen bevat een %2$sovergangswoord%3$s of zin. Dit is prima!"
			],
			"%1$s of the words contain %2$sover %3$s syllables%4$s, which is less than or equal to the recommended maximum of %5$s.":[
				"%1$s van de woorden bevat %2$s meer dan %3$s lettergrepen, wat minder of gelijk is dan het aanbevolen maximum van %5$s."
			],
			"%1$s of the words contain %2$sover %3$s syllables%4$s, which is more than the recommended maximum of %5$s.":[
				"%1$s van de woorden bevat %2$s meer dan %3$s lettergrepen, wat meer is dan het aanbevolen maximum van %5$s."
			],


			"The copy scores %1$s in the %2$s test, which is considered %3$s to read. %4$s":[
				"De tekst scoort %1$s in de %2$s test, wat beschouwd wordt als %3$s te lezen. %4$s"
			],
			"very easy":[
				"zeer gemakkelijk"
			],
			"easy":[
				"gemakkelijk"
			],
			"fairly easy":[
				"betrekkelijk gemakkelijk"
			],
			"ok":[
				"goed"
			],
			"fairly difficult":[
				"betrekkelijk moeilijk"
			],
			"difficult":[
				"moeilijk"
			],
			"very difficult":[
				"zeer moeilijk"
			],


			"Try to make shorter sentences to improve readability.":[
				"probeer kortere zinnen te maken om de leesbaarheid te vergroten."
			],
			"Try to make shorter sentences, using less difficult words to improve readability.":[
				"probeer kortere zinnen te maken met eenvoudiger woorden om de leesbaarheid te vergroten."
			],
			"None of the paragraphs are too long, which is great.":[
				"Geen van de paragrafen is te lang, wat goed is."
			],
			"%1$d of the paragraphs contains more than the recommended maximum of %2$d words. Are you sure all information is about the same topic, and therefore belongs in one single paragraph?":[
				"%1$d van de de paragrafen bevat meer de het aanbevolen maximum van %2$d woorden. Weet je zeker dat alle informatie over hetzelfde onderwerp gaat en in een paragraaf hoort?",
				"%1$d van de de paragrafen bevat meer de het aanbevolen maximum van %2$d woorden. Weet je zeker dat alle informatie over hetzelfde onderwerp gaat en in een paragraaf hoort?",
			],
			"%1$s of the sentences contain %2$smore than %3$s words%4$s, which is less than or equal to the recommended maximum of %5$s.": [
				"%1$s van de zinnen bevat %2$s meer dan %3$s woorden%4$s, wat minder of gelijk is aan het aanbevolen maximum van %5$s."
			],
			"%1$s of the sentences contain %2$smore than %3$s words%4$s, which is more than the recommended maximum of %5$s. Try to shorten the sentences.": [
				"%1$s van de zinnen bevat %2$s meer dan %3$s woorden%4$s, wat meer is dan het aanbevolen maximum van %5$s. Probeer korteren zinnen te maken."
			],


			"Great job with using %1$ssubheadings%2$s!":[
				"Het gebruik van %1$ssubkoppen%2$s is prima."
			],
			"%1$d section of your text is longer than %2$d words and is not separated by any subheadings. Add %3$ssubheadings%4$s to improve readability.":[
				"%1$d sectie van de tekst is langer dan %2$d woorden en wordt niet gescheiden door subkoppen. Voeg %3$ssubkoppen%4$s toe om leesbaarheid te vergroten",
				"%1$d secties van de tekst zijn langer dan %2$d woorden en worden niet gescheiden door subkoppen. Voeg %3$ssubkoppen%4$s toe om leesbaarheid te vergroten",
			],
			"You are not using any subheadings, although your text is rather long. Try and add  some %1$ssubheadings%2$s.":[
				"Je gebruikt geen subkoppen, maar de tekst is tamelijk lang. Probeer %1$sssubkoppen%2$s toe te voegen"
			],
			"You are not using any %1$ssubheadings%2$s, but your text is short enough and probably doesn't need them.":[
				"Je gebruikt geen subkoppen, maar dat de tekst zo kort dat deze niet nodig zijn"
			],


			"No %1$simages%2$s appear in this page, consider adding some as appropriate.":[
				"Er komen geen %1$safbeeldingen%2$s voor op deze pagina, overweeg deze toe te voegen."
			],
			"The %1$simages%2$s on this page contain alt attributes with the focus keyword.":[
				"De %1$safbeeldingen%2$s op deze pagina bevatten een alt-attribuut met het trefwoord."
			],
			"The %1$simages%2$s on this page do not have alt attributes containing the focus keyword.":[
				"De %1$safbeeldingen%2$s op deze pagina bevatten een geen alt-attribuut met het trefwoord."
			],
			"The %1$simages%2$s on this page contain alt attributes.":[
				"De %1$safbeeldingen%2$s op deze pagina bevatten een alt-attribuut."
			],
			"The %1$simages%2$s on this page are missing alt attributes.":[
				"De %1$safbeeldingen%2$s op deze pagina bevatten geen alt-attribuut."
			],

			"No %1$sinternal links%2$s appear in this page, consider adding some as appropriate.":[
				"Er komen geen %1$sinterne links%2$s voor op deze pagina, overweeg deze toe te voegen."
			],
			"This page has %1$s %2$sinternal link(s)%3$s, all nofollowed.":[
				"Deze pagina heeft %1$s %2$sinterne link(s)%3$s, allemaal nofollow"
			],
			"This page has %1$s %2$sinternal link(s)%3$s.":[
				"Deze pagina heeft %1$s %2$sinterne link(s)%3$s"
			],
			"This page has %1$s nofollowed %2$sinternal link(s)%3$s and %4$s normal internal link(s).":[
				"Deze pagina heeft %1$s nofollow %2$sinterne link(s)%3$s en %4$s normale interne link(s)."
			],

			"No %1$soutbound links%2$s appear in this page, consider adding some as appropriate.":[
				"Er komen geen %1$sexterne links%2$s voor op deze pagina, overweeg deze toe te voegen."
			],
			"This page has %1$s %2$soutbound link(s)%3$s, all nofollowed.":[
				"Deze pagina heeft %1$s %2$sexterne link(s)%3$s, allemaal nofollow"
			],
			"This page has %1$s %2$soutbound link(s)%3$s.":[
				"Deze pagina heeft %1$s %2$sexterne link(s)%3$s"
			],
			"This page has %1$s nofollowed %2$soutbound link(s)%3$s and %4$s normal outbound link(s).":[
				"Deze pagina heeft %1$s nofollow %2$sexterne link(s)%3$s en %4$s normale externe link(s)."
			],


			"The %1$sSEO title%2$s is too short. Use the space to add keyword variations or create compelling call-to-action copy.":[
				"De %1$sSEO titel%2$s is te kort. Gebruik de ruimte voor trefwoord variaties of voor een aantrekkelijke call-to-action."
			],
			"The %1$sSEO title%2$s has a nice length.":[
				"De %1$sSEO titel%2$s heeft een goede lengte."
			],
			"The %1$sSEO title%2$s is wider than the viewable limit.":[
				"De %1$sSEO titel%2$s is langer dan de beschikbare ruimte."
			],
			"Please create an %1$sSEO title%2$s.":[
				"Voeg een %1$sSEO titel%2$s toe."
			],


			"The slug for this page is a bit long, consider shortening it.":[
				"De url voor deze pagina is een beetje lang, overweeg om deze korter te maken."
			],
			"The slug for this page contains a %1$sstop word%2$s, consider removing it.":[
				"De url voor deze pagina bevat een %1$sstopwoord%2$s, overweeg om deze te verwijderen.",
				"De url voor deze pagina bevat %1$sstopwoorden%2$s, overweeg om deze te verwijderen."
			],


			"A meta description has been specified, but it %1$sdoes not contain the focus keyword%2$s.":[
				"De meta-omschrijving is gegeven, maar het bevat niet het trefwoord"
			],
			"No %1$smeta description%2$s has been specified. Search engines will display copy from the page instead.":[
				"Er is geen %1$smeta-omschrijving%2$s gegeven. Zoekmachines zullen conten uit de pagina weergeven."
			],
			"The %1$smeta description%2$s is under %3$d characters long. However, up to %4$d characters are available.":[
				"De %1$smeta-omschrijving%2$s is minder dan %3$d karakters lang, maar er zijn %4$d karakters beschikbaar."
			],
			"The %1$smeta description%2$s is over %3$d characters. Reducing the length will ensure the entire description will be visible.":[
				"De %1$smeta-omschrijving%2$s is langer dan %3$d karakters. Maak de omschrijving korter om zeker te weten dat deze helemaal zichtbaar is."
			],
			"The %1$smeta description%2$s has a nice length.":[
				"De %1$smeta-omschrijving%2$s heeft een goede lengte."
			],
			"The meta description %1$scontains the focus keyword%2$s.":[
				"De meta beschrijving %1$sbevat het trefwoord%2$s.",
			],
			"The meta description contains no sentences %1$sover %2$s words%3$s.":[
				"De meta-omschrijving bevat geen zinnen met meer dan %2$s woorden."
			],
			"The meta description contains %1$d sentence %2$sover %3$s words%4$s. Try to shorten this sentence.":[
				"De meta-omschrijving bevat %1$d zin met meer dan %3$s woorden. Probeer deze zin korter te maken.",
				"De meta-omschrijving bevat %1$d zinnen met meer dan %3$s woorden. Probeer deze zinnen korter te maken.",
			],




			"No %1$sfocus keyword%2$s was set for this page. If you do not set a focus keyword, no score can be calculated.":[
				"Er is geen %1$strefwoord%2$s ingevuld. Als er geen trefwoord aanwezig is, kan er geen score bepaald worden."
			],
			"The focus keyword contains a stop word. This may or may not be wise depending on the circumstances. %1$sLearn more about the stop words%2$s.":[
				"Het trefwoord bevat een stopwoord. Dit kan wel of niet verstandig zijn. %1$sLees meer over stopwoorden%2$s.",
				"Het trefwoord bevat meerdere stopwoorden. Dit kan wel of niet verstandig zijn. %1$sLees meer over stopwoorden%2$s."
			],



			"The focus keyword doesn't appear in the %1$sfirst paragraph%2$s of the copy. Make sure the topic is clear immediately.":[
				"Het trefwoord is niet aanwezig in de %1$seerste paragraaf%2$s van de tekst. Zorg ervoor dat het onderwerp direct duidelijk is."
			],
			"The focus keyword appears in the %1$sfirst paragraph%2$s of the copy.":[
				"Het trefwoord komt voor in de %1$seerste paragraaf%2$s van de tekst."
			],
			"The focus keyword does not appear in the %1$sURL%2$s for this page. If you decide to rename the URL be sure to check the old URL 301 redirects to the new one!":[
				"Het trefwoord komt niet voor in de %1$sURL%2$s van deze pagina. "
			],
			"The focus keyword appears in the %1$sURL%2$s for this page.":[
				"Het trefwoord komt voor in de %1$sURL%2$s van deze pagina. "
			],
			"The focus keyword '%1$s' does not appear in the %2$sSEO title%3$s.":[
				"Het trefwoord '%1$s' komt niet in de %2$sSEO title%3$s voor."
			],
			"The focus keyword appears in %1$d (out of %2$d) %3$ssubheadings%4$s in your copy.":[
				"Het trefwoord komt voor in %1$s van %2$d %3$ssubkoppen%4$s in de tekst."
			],
			"You have not used the focus keyword in any %1$ssubheading%2$s (such as an H2) in your copy.":[
				"Het trefwoord komt niet voor in %1$ssubkoppen%1$s in de tekst."
			],
			"The %1$sSEO title%2$s contains the focus keyword, at the beginning which is considered to improve rankings.":[
				"De %1$sSEO titel%2$s bevat het trefwoord aan het begint, wat goed werkt voor een betere ranking."
			],
			"The %1$sSEO title%2$s contains the focus keyword, but it does not appear at the beginning; try and move it to the beginning.":[
				"De %1$sSEO titel%2$s bevat het trefwoord, maar niet aan het begin. Probeer de titel met het trefwoord te beginnen."
			],
			"Use your keyword or synonyms more often in your text so that we can check %1$skeyword distribution%2$s.":[
				"Gebruik het trefwoord vaker in de tekst zodat de %1$strefwoord verdeling%2$s gecheckt kan worden"
			],
			"Large parts of your text do not contain the keyword. Try to %1$sdistribute%2$s the keyword more evenly.":[
				"Grote delen van de tekst bevatten het trefwoord niet. Probeer het trefwoord beter te %1$sverdelen%2$s.",
			],
			"Some parts of your text do not contain the keyword. Try to %1$sdistribute%2$s the keyword more evenly.":[
				"Sommige delen van de tekst bevatten het trefwoord niet. Probeer het trefwoord beter te %1$sverdelen%2$s.",
				"Sommige delen van de tekst bevatten het trefwoord niet. Probeer het trefwoord beter te %1$sverdelen%2$s."
			],
			"Your keyword is %1$sdistributed%2$s evenly throughout the text. That's great.":[
				"Het trefwoord is goed over de tekst %1$sverdeeld%2$s.",
				"Het trefwoord is goed over de tekst %1$sverdeeld%2$s.",
			],

			"The %1$skeyphrase%2$s is over 10 words, a keyphrase should be shorter.":[
				"The %1$skernzin%2$s is meer dan 10 woorden, en kernzin hoort korter te zijn.",
			],




			"The exact-match %3$skeyword density%4$s is %1$s, which is too low; the focus keyword was found %2$d time.":[
				"De %3$strefwoord dichtheid%4$s is %1$s, wat te laag is. Het trefwoord komt %2$d keer voor.",
				"De %3$strefwoord dichtheid%4$s is %1$s, wat te laag is. Het trefwoord komt %2$d keer voor."
			],
			"The exact-match %3$skeyword density%4$s is %1$s, which is great; the focus keyword was found %2$d time.":[
				"De %3$strefwoord dichtheid%4$s is %1$s, wat goed is. Het trefwoord komt %2$d keer voor.",
				"De %3$strefwoord dichtheid%4$s is %1$s, wat goed is. Het trefwoord komt %2$d keer voor."
			],
			"The exact-match %4$skeyword density%5$s is %1$s, which is over the advised %3$s maximum; the focus keyword was found %2$d time.":[
				"De %4$strefwoord dichtheid%5$s is %1$s, wat hoger is dan het aanbevolen maximum van %3$s. Het trefwoord komt %2$d keer voor.",
				"De %4$strefwoord dichtheid%5$s is %1$s, wat hoger is dan het aanbevolen maximum van %3$s. Het trefwoord komt %2$d keer voor.",
			],
			"The exact-match %4$skeyword density%5$s is %1$s, which is way over the advised %3$s maximum; the focus keyword was found %2$d time.":[
				"De %4$strefwoord dichtheid%5$s is %1$s, wat veel hoger is dan het aanbevolen maximum van %3$s. Het trefwoord komt %2$d keer voor.",
				"De %4$strefwoord dichtheid%5$s is %1$s, wat veel hoger is dan het aanbevolen maximum van %3$s. Het trefwoord komt %2$d keer voor.",
			],




			"The text contains %1$d word.":[
				"De tekst bevat %1$d woord",
				"De tekst bevat %1$d woorden"
			],
			"This is more than or equal to the %2$srecommended minimum%3$s of %4$d word.":[
				"Dit is meer of gelijk aan het %2$saanbevolen minimum%3$s van %4$d woord.",
				"Dit is meer of gelijk aan het %2$saanbevolen minimum%3$s van %4$d woorden."
			],
			"This is slightly below the %2$srecommended minimum%3$s of %4$d word. Add a bit more copy.":[
				"Dit is iets minder dan het %2$saanbevolen minimum%3$s van %4$d woord. Voeg iets meer tekst toe.",
				"Dit is iets minder dan het %2$saanbevolen minimum%3$s van %4$d woorden. Voeg iets meer tekst toe."
			],
			"This is below the %2$srecommended minimum%3$s of %4$d word. Add more content that is relevant for the topic.":[
				"Dit is minder dan het %2$saanbevolen minimum%3$s van %4$d woord. Voeg meer relevante content toe.",
				"Dit is minder dat het %2$saanbevolen minimum%3$s van %4$d woorden. Voeg meer relevante content toe."
			],
			"This is far below the %2$srecommended minimum%3$s of %4$d word. Add more content that is relevant for the topic.":[
				"Dit is lager dan het %2$saanbevolen minimum%3$s van %4$d woord. Voeg meer relevante content toe.",
				"Dit is lager dan het %2$saanbevolen minimum%3$s van %4$d woorden. Voeg meer relevante content toe."
			],
		}
	}

});

//_____ manager/form/element/Select.js _____//

define('manager.form.element.Select')
.use('yellow.Arr')
.as(function(y, Arr) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		var multiple = _scope.data('multiple');
		var value = scope.data('value');
		if(multiple && ! y.isArray(value)) {
			value = [];
		}
		
		_scope.find('option').each(function() {
			var element = y(this);
			if(multiple && Arr.has(element.val(), value)) {
				element.attr('selected', 'selected');
			} else if(! multiple && element.val() == value) {
				element.attr('selected', 'selected');
				// Safari
				element.prop('selected', true);
			}
		})
	}
	
	this.value = function()
	{
		var multiple = _scope.data('multiple');
		if(multiple) {
			var value = [];
		} else {
			var value = null;
		}
		
		_scope.find('option:selected').each(function() {
			if(multiple) {
				value.push(y(this).val());
			} else {
				value = y(this).val();
			}
		})
		return value;
	}
});

//_____ manager/form/element/Status.js _____//

define('manager.form.element.Status')
.as(function(y) {
	
	var _scope;
	var _value;
	
	this.start = function(scope) 
	{
		_scope = scope;
		_value = scope.data('value');
		var status = scope.fetch('status');
		
		
		if(_value === 'live') {
			scope.fetch('live').show();
			scope.fetch('edit').hide();
			status.addClass('text-success');
		} else {
			scope.fetch('live').hide();
			scope.fetch('edit').show();
			status.removeClass('text-success');
		}
		
		
		scope.fetch('option').click(function(){
			var option = y(this);
			_value = option.data('value');
			status.text(option.fetch('label').text());
			if(_value === 'live') {
				scope.fetch('live').show();
				scope.fetch('edit').hide();
				status.addClass('text-success');
			} else {
				scope.fetch('live').hide();
				scope.fetch('edit').show();
				status.removeClass('text-success');
			}
		});
	}
	
	
	this.value = function()
	{
		return _value;
	}

});

//_____ manager/form/Link.js _____//

define('manager.form.Link')
.use('manager.Callback')
.as(function(y, Callback) {
	
	
	this.start = function(scope) 
	{
		// pick up changes in the selected url, to use in title
		scope.fetch('url').change(function(){
			var changed = y(this);
			if(changed.data('title') && scope.fetch('element-title').invoke('value') == '' ) {
				scope.fetch('element-title').invoke('value', changed.data('title'));
			}
		});
		
		scope.fetch('submit').click(function(){
			var data = {
				url: scope.fetch('element-url').invoke('value'),
				title: scope.fetch('element-title').invoke('value'),
				blank: scope.fetch('element-blank').invoke('value'),
				nofollow: scope.fetch('element-nofollow').invoke('value'),
			}
			Callback.invoke(scope.data('callback'), data);
		});
	}
});

//_____ manager/form/element/Url.js _____//

define('manager.form.element.Url')
.use('manager.Dialog')
.use('manager.Callback')
.as(function(y, Dialog, Callback) {
	
	var _scope;
	
	
	this.start = function(scope) 
	{
		_scope = scope;
		
		scope.find('input[type=text]').val(scope.data('value'));
		
		scope.fetch('preset').click(function(){
			var clicked = y(this);
			// a preset was clicked, build url from that
			y.ajax(scope.data('url_construct'), {
				method: 'POST',
				dataType: 'json',
				data: {
					route: y(this).data('route'),
					info: JSON.stringify(clicked.data('info'))
				}
			}).done(function(data){
				// set the correct url
				scope.find('input[type=text]').val(data.url);
				// emit a change, so the form can pick up the title
				scope
				.data('title', clicked.data('title'))
				.change();
			});
		})
		
		
		scope.fetch('select').click(function(){
			// a module select was clicked, collect item data and build url from that
			var clicked = y(this);
			var dialog;
			var callback = Callback.register(function(data) {
				dialog.remove();
				y.ajax(scope.data('url_construct'), {
					method: 'POST',
					dataType: 'json',
					data: {
						module: clicked.data('module'),
						info: JSON.stringify({params: data})
					}
				}).done(function(urlData){
					// set the correct url,
					scope.find('input[type=text]').val(urlData.url);
					
					// emit a change, so the form can pick up the title
					scope
					.data('title', data.title || '')
					.change();
				});
			});
			dialog = Dialog.iframe(clicked.data('url').replace('{{callback}}', callback));
		})
	}
	
	this.value = function(value)
	{
		return _scope.find('input[type=text]').val();
	}
});

//_____ manager/form/Menu.js _____//

define('manager.form.Menu')
.use('manager.Callback')
.as(function(y, Callback) {

	this.start = function(scope) 
	{
		// update Title field when a new link was chosen.
		scope.fetch('link').change(function(){
			var changed = y(this);
			if(changed.data('title') && scope.fetch('element-title').invoke('value') == '' ) {
				scope.fetch('element-title').invoke('value', changed.data('title'));
			}
		});
		
		
		scope.fetch('element-type').change(function(){
			var val = y(this).invoke('value');
			if(val == 'default') {
				scope.fetch('element-html').fetch('group', 'closest').hide();
				scope.fetch('element-link').fetch('group', 'closest').show();
				scope.fetch('element-title').fetch('group', 'closest').show();
				
			} else if(val == 'html') {
				scope.fetch('element-html').fetch('group', 'closest').show();
				scope.fetch('element-link').fetch('group', 'closest').hide();
				scope.fetch('element-title').fetch('group', 'closest').hide();
			} else {
				scope.fetch('element-html').fetch('group', 'closest').hide();
				scope.fetch('element-link').fetch('group', 'closest').hide();
				scope.fetch('element-title').fetch('group', 'closest').hide();
			}
		}).change();
	}
});

//_____ manager/form/element/Menu.js _____//

define('manager.form.element.Menu')
.as(function(y) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		var value = scope.data('value');
		_scope.find('option').each(function() {
			var element = y(this);
			if( element.val() == value) {
				element.attr('selected', 'selected');
			}
		})
	}
	
	this.value = function()
	{
		return _scope.find('option:selected').val();
	}
});

//_____ manager/form/Embed.js _____//

define('manager.form.Embed')
.use('manager.Callback')
.as(function(y, Callback) {
	
	this.start = function(scope) 
	{
		scope.fetch('submit').click(function(){
			Callback.invoke(scope.data('callback'), scope.fetch('element-image').data('value'));
		});
	}
});

//_____ manager/form/element/Checkbox.js _____//

define('manager.form.element.Checkbox')
.use('yellow.Arr')
.as(function(y, Arr) {
	
	var _scope;
	
	this.start = function(scope) 
	{
		_scope = scope;
		_scope.find('input[type=checkbox]').each(function() {
			var element = y(this);
			if(Arr.has(element.val(), scope.data('value'))) {
				element.attr('checked', 'checked');
			}
		})
	}
	
	this.value = function()
	{
		var value = [];
		_scope.find('input[type=checkbox]:checked').each(function() {
			value.push(y(this).val());
		})
		return value;
	}
});

//_____ manager/form/element/File.js _____//

define('manager.form.element.File')
.use('yellow.View')
.use('manager.Dialog')
.use('manager.Callback')
.use('manager.Message')
.as(function(y, View, Dialog, Callback, Message, self)
{
	var _scope;
	
	var _value;
	
	var _multiple;
	
	var _max;
	
	var _dropzone;
	
	var _current = null;

	this.start = function(scope)
	{
		_scope = scope;
		
		_value = scope.data('value');
		
		_multiple = scope.data('multiple');
		
		_max = _multiple ? scope.data('max') : 1;
		
		_dropzone = new Dropzone(scope.fetch('zone')[0] , {
			maxFiles: _max,
			url: _scope.data('url_create'),
			success: function(file, response) {
				this.removeFile(file);
				response = JSON.parse(response);
				if(response.success && y.isSet(response.items[0])) {
					update(response.items[0].data);
				}
				
				if(! response.success && response.errors.length > 0) {
					Message.make(response.errors[0], 'error');
				}
				refresh();
			},
			init: function() {
				this.on('addedfile', function(file){
					if(_scope.fetch('file').length + this.getQueuedFiles().length >= _max) {
						this.removeFile(file);
					}
				});
			}
		});

		
		if(_multiple) {
			var files = y.isArray(_value) ? _value : [];
		} else {
			var files = y.isObject(_value) ? [ _value ] : [];
		}
		
		for(var i = 0; i < files.length; i++ ) {
			update(files[i]);
		}
		refresh();
	}
	
	
	this.value = function()
	{
		return _value;
	}
	
	
	/**
	 * Incoming data from upload
	 * @param {type} data
	 * @returns {undefined}
	 */
	var update = function(data)
	{
		// make sure junction is set
		data.junction = data.junction || {};
		
		// create a relation element
		var file = View.make(_scope.template('file')).element(data);
		
		// set the data
		file.data('data', data);
		
		// update values when changing junction vals
		file.fetch('junction').change(refresh);

		// Delete button
		file.fetch('delete').click(function(e){
			var dialog = Dialog.make({
				title: _scope.data('title'),
				body: _scope.data('message'),
				close: true,
				width: 600,
				height: 300,
				buttons: [
					{type: 'primary', label: _scope.data('instance'), action: function(){
						file.remove();
						refresh();
						dialog.remove(); 
					}},
					{type: 'secondary', label: _scope.data('original'), action: function(){
						file.remove();
						y.ajax(_scope.data('url_delete').replace('{{id}}', data.id), {
							type : 'POST',
							data: {
								csrf: _scope.data('csrf')
							}
						})
						refresh();
						dialog.remove()
					}},
				]
			});
			return dialog;
		});
		
		_scope.fetch('container').append(file);
	}
	
	
	var refresh = function()
	{
		// get the current images
		var files = _scope.fetch('file');
		
		// show / hide add button
		if( (_multiple && files.length >= _max) || (! _multiple && files.length >= 1) ) {
			_scope.fetch('zone').hide();
		} else {
			_scope.fetch('zone').show();
		}
		
		// get the data in full and for the serverside
		var full = [];
		var value = [];
		files.each(function(){
			var file = y(this);
			var data = file.data('data');
			data.junction = {};
			file.fetch('junction').each(function(){
				var junction = y(this);
				data.junction[junction.data('name')] = junction.val();
			});
			full.push(data);
			
			var item = data.junction;
			item.id = data.id;
			value.push(item);
		});
			

		if (! _multiple) {
			if(full.length > 0) {
				// single relation: only use the first
				full = full[0];
				value = value[0];
			} else {
				full = {id: 0}
				value = 0
			}
		} 
		
		// set entire dataset for other purposes
		_scope.data('value', full);
		
		// save value as the internal value
		_value = value;

	
		// make sortable
		if(_multiple && _max > 1) {
			_scope.fetch('file').addClass('movable');
			_scope.fetch('container').sortable({
				items: '[y-name^=file]', 
				containment: _scope,
				tolerance: 'pointer',
				placeholder: 'placeholder',
				stop: refresh,
			});
		}
	}
});

//_____ manager/form/element/Home.js _____//

define('manager.form.element.Home')
.use('yellow.View')
.use('manager.Post')
.as(function(y, View, Post){
	
	this.start = function(scope)
	{
		var post = Post.make(scope.data('url'));
		var state = '';
		var reload = function(){
			var values = scope.fetch('form', 'closest').invoke('values');
			var contents =  btoa(encodeURIComponent(JSON.stringify(values)).replace(/%([0-9A-F]{2})/g,
				function (match, p1) {
					return String.fromCharCode('0x' + p1);
			}));
			
			if(contents !== state) {
				post.submit({values: contents}, 'preview-inline')
			}
			
			state = contents;
		}
		
		setInterval(reload, 200);
		reload();
	}
});

//_____ manager/index/Update.js _____//

define('manager.index.Update')
.as(function(y) {
	this.start = function(scope) 
	{
		scope.click(function(e){
			document.location.href = scope.data('url')
		});
	}
});

//_____ manager/form/element/Article.js _____//

define('manager.form.element.Article')
.as(function(y){
	
	this.start = function(scope)
	{
		var video = scope.fetch('element-video').fetch('group', 'closest');
		var audio = scope.fetch('element-audio').fetch('group', 'closest');
		var images = scope.fetch('element-images').fetch('group', 'closest');
		var live = scope.fetch('live');
		var intro = scope.fetch('element-intro').fetch('group', 'closest');
		var summary = scope.fetch('element-summary').fetch('group', 'closest');
		var excerptLive = scope.fetch('element-excerpt_live').fetch('group', 'closest');
		var body = scope.fetch('element-body').fetch('group', 'closest');
		var image = scope.fetch('element-image').closest('.card');
		
		scope.fetch('element-type').change(function(){
			video.hide();
			audio.hide();
			live.hide();
			summary.hide();
			excerptLive.hide();
			intro.show();
			// dont gide iamges in block editor
			if(images.fetch('editor', 'closest').length == 0) { 
				images.hide();
			}
			
			body.hide();
			image.hide();
			
			switch(y(this).val()) {
				case 'default':
					image.show();
					body.show();
					break;
				case 'video':
					video.show();
					image.show();
					body.show();
					break;
				case 'podcast':
					image.show();
					audio.show();
					body.show();
					break;
				case 'gallery':
					image.show();
					images.show();
					break;
				case 'live':
					image.show();
					summary.show();
					excerptLive.show();
					intro.hide();
					live.show();
					break;
			}
		}).change();
	}
});

//_____ manager/form/element/TimePublication.js _____//

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

//_____ manager/form/element/BannerPositions.js _____//

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

//_____ manager/index/Preview.js _____//

define('manager.index.Preview')
.use('manager.Post')
.as(function(y, Post) {
	this.start = function(scope) 
	{
		scope.click(function(){
			var contents =  btoa(encodeURIComponent(JSON.stringify(scope.data('data'))).replace(/%([0-9A-F]{2})/g,
				function (match, p1) {
					return String.fromCharCode('0x' + p1);
				}
			));
			Post.make(scope.data('url')).submit({values: contents}, 'preview');
		})
	}
});


//_____ manager/form/Campaign.js _____//

define('manager.form.Campaign')
.as(function(y) {

	this.start = function(scope) 
	{
		
		var interval = setInterval(function(){
			var parts = window.location.href.split('/');
			var last = parts.pop();
			if( new Number(last) == last) {
				y.log('ok');
				scope.fetch('advertorial').attr('href', scope.fetch('advertorial').attr('href').replace('{{id}}', last));
				scope.fetch('advertorials').attr('href', scope.fetch('advertorials').attr('href').replace('{{id}}', last));
				//scope.fetch('banner').attr('href', scope.fetch('banner').attr('href').replace('{{id}}', last));
				//scope.fetch('banners').attr('href', scope.fetch('banners').attr('href').replace('{{id}}', last));
				scope.fetch('create').hide();
				scope.fetch('update').show();
				clearInterval(interval);
			} else {
				scope.fetch('create').show();
				scope.fetch('update').hide();
			}
		}, 100);
	}
});

//_____ manager/index/filter/Author.js _____//

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


//_____ manager/index/filter/Date.js _____//

define('manager.index.filter.Date')
.as(function(y) {
	this.start = function(scope) 
	{
		scope.fetch('input').change(function(){
			if(y(this).val()) {
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


//_____ manager/live/Manage.js _____//

define('manager.live.Manage')
.use('yellow.View')
.use('manager.Dialog')
.use('manager.Loading')
.use('manager.Message')
.as(function(y, View, Dialog, Loading, Message) {
	
	var _prototype;
	var _id;
	var _scope;
	var _values;
	
	
	this.start = function(scope) 
	{
		_scope = scope;
		
		_prototype = _scope.fetch('prototype');

		freshForm();
		posts();
		
		_scope.fetch('publish').click(function(){
			if(_id) {
				update(_id, values())
			} else {
				create(values());
			}
		})
		
		_scope.fetch('draft').click(function(){
			if(_id) {
				update(_id, values(), true);
			} else {
				create(values(), true);
			}
		})
		
	
		setInterval(function(){
			posts()
		}, 30000)
	}
	
	
	
	var values = function()
	{
		return {
			body: _scope.fetch('form').fetch('element-body').invoke('value'),
		}
	}
	
	
	var freshForm = function()
	{
		_id = null;
		createForm({
			body: [{
				type: 'html', body: ''
			}]
		})
		_scope.fetch('form').fetch('element-body').fetch('block-update').click();
	}
	
	
	
	var createForm = function(values)
	{
		if(_id) {
			_scope.fetch('create').hide();
			_scope.fetch('update').show();
		} else {
			_scope.fetch('create').show();
			_scope.fetch('update').hide();
		}
		// Set current value
		_values = values;
		// Create new block element
		var blocks = _prototype.make();
		// Set value in the blocks element
		blocks.fetch('element').data('value', values.body || '')
		// Add it to the html
		_scope.fetch('form').empty().append(blocks);
		// Show it
		blocks.show();
		// Start it up
		blocks.start();
	}
	
	
	
	var unsaved = function()
	{
		if(JSON.stringify(values()) == JSON.stringify(_values)) {
			return false;
		} else {
			return true;
		}
	}

	
	var create = function(data, draft) {
		if(draft) {
			data.status = 'draft'
		} else {
			data.status = 'live'
		}
		Loading.show();
		y.ajax(_scope.data('url').create,{
			method: 'post',
			data: data,
			dataType: 'json'
		}).done(function(result){
			posts();
			freshForm();
		}).always(function(){
			Loading.hide()
		})
	}
	
	
	var update = function(id, data, draft) {
		if(draft) {
			data.status = 'draft'
		} else {
			data.status = 'live'
		}
		Loading.show();
		y.ajax(_scope.data('url').update.replace('{{id}}', id),{
			method: 'post',
			data: data,
			dataType: 'json'
		}).done(function(result){
			posts();
			freshForm();
		}).always(function(){
			Loading.hide()
		})
	}
	
	
	var posts = function()
	{
		y.ajax(_scope.data('url').items,{
			dataType: 'json'
		}).done(function(data){
			if(y.isArray(data)) {
				_scope.fetch('posts').empty();
				_scope.fetch('drafts').empty();
				for(var i = 0; i < data.length; i++) {
					if(data[i].status == 'draft') {
						_scope.fetch('drafts').append(post(data[i]))
					} else {
						_scope.fetch('posts').append(post(data[i]))
					}
				}
			}
		})
	}
	
	
	var post = function(data)
	{
		var item = View.make(_scope.template('post'),{}, {
			time: function(ts) {
	
				return new Date(ts * 1000).toJSON().substring(0,19).replace('T',' ');
			}
		}).element(data);	
		
		if(y.isArray(data.body)) {
			for(var i = 0; i < data.body.length; i++) {
				var block = data.body[i];
				
				if(block.type == 'html' && block.body) {
					item.fetch('body').append(y(block.body))
				} else if(block.type == 'article' && block.article) {
					item.fetch('body').append(View.make(_scope.template('block_article')).element({
						article: block.article
					}))
				} else if(block.type == 'image' && block.image) {
					item.fetch('body').append(View.make(_scope.template('block_image')).element({
						image:block.image
					}))
				} else if(block.type == 'link' && block.link) {
					item.fetch('body').append(View.make(_scope.template('block_link')).element({
						link: block.link
					}))
				} else if(block.type == 'embed' && block.embed) {
					//item.fetch('body').append(y(block.embed))
					item.fetch('body').append(y('<div></div>').text(block.embed))

				}
			}
		}
		
		item.fetch('update').click(function() {
			if(unsaved()) {
				Dialog.confirm('Discard changes?', 'You have unsaved changes in your current post. Discard these changes?', function(){
					_id = data.id;
					createForm({
						body: data.body
					});
				})
			} else {
				_id = data.id;
				createForm({
					body: data.body
				});
			}
		})
		
		item.fetch('delete').click(function() {
			Dialog.confirm('Delete post?', 'Do you want to delete this post?', function(){
				Loading.show();
				y.ajax(_scope.data('url').delete.replace('{{id}}', data.id),{
					method: 'post',
					dataType: 'json'
				}).done(function(result){
					
					if(_id == data.id) {
						// deleted the one that was editing
						_id = null;
						freshForm();
					}
				}).always(function(){
					posts();
					Loading.hide()
				})
			})
		})
		
		return item;
	}
});


//_____ manager/form/markup/Live.js _____//

define('manager.form.markup.Live')
.as(function(y){
	
	this.start = function(scope)
	{
		var interval = setInterval(function(){
			var url = new URL(window.location.href);
			var part = url.pathname.split('/').slice(-1)[0];
			if (typeof part === 'string' && !isNaN(part)) {
				var btn = scope.fetch('posts');	
				btn.attr('href', btn.attr('href').replace('{{id}}', part)).show();
				scope.fetch('inactive').hide();
				clearInterval(interval);
			}
		}, 500)
	}
});

//_____ manager/form/element/Bullets.js _____//

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