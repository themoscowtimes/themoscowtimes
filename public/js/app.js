

//_____ Main.js _____//

define('Main')
.as(function(y){
	this.start = function(scope)
	{
		scope.fetch('lightbox').simpleLightbox();
	}
});

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

//_____ Youtube.js _____//

define('Youtube')
.as(function(y)
{
	this.start = function(scope)
	{
		var video = scope.data('video');

		scope.fetch('poster').click(function(){
			y(this).hide();
			scope.fetch('player').html('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' + video + '?autoplay=1&loop=1&rel=0&wmode=transparent" frameborder="0" allowfullscreen wmode="Opaque"></iframe>');
		});
	}
});

//_____ Events.js _____//

define('Events')
.as(function(y)
{
	var _scope;
	
	var _from = 0;
	var _to = 9999999999999999;
	var _type = '';
	
	this.start = function(scope)
	{
		_scope = scope
		
		scope.fetch('day').change(function(){
			switch(y(this).val()) {
				case 'today':
					_from = moment().startOf('day').format('X');
					_to = moment().endOf('day').format('X');
					break;
				case 'tomorrow':
					_from = moment().add(1,'d').startOf('day').format('X');
					_to = moment().add(1,'d').endOf('day').format('X');
					break;
				case 'weekend':
					var day = moment().format('d');
					// set sunday to 7 instead of 0
					day = day == 0 ? 7 : day;
					_from = moment().add(6 - day, 'd').startOf('day').format('X');
					_to = moment().add(7 - day,'d').endOf('day').format('X');
					break;
				case 'week':
					_from = moment().startOf('week').format('X');
					_to = moment().endOf('week').format('X');
					break;	
				case 'nextweek':
					_from = moment().add(1, 'w').startOf('week').format('X');
					_to = moment().add(1, 'w').endOf('week').format('X');
					break;		
				case 'month':
					_from = moment().startOf('month').format('X');
					_to = moment().endOf('month').format('X');
					break;	
				case 'nextmonth':
					_from = moment().add(1, 'M').startOf('month').format('X');
					_to = moment().add(1, 'M').endOf('month').format('X');
					break;		
				default:
					_from = 0;
					_to = 9999999999999999;
			}
			filter()
		});


		scope.fetch('date').change(function(){
			var value = y(this).invoke('value');
			_from = moment(value).startOf('day').format('X');
			_to = moment(value).endOf('day').format('X');
			filter();
		});

		
		scope.fetch('type').change(function(){
			_type = y(this).val();
			filter();
		});
	}
	
	
	var filter = function() 
	{
		_scope.fetch('event').hide();
		_scope.fetch('event').each(function(){
			if(y(this).data('date') >= _from && y(this).data('date') <= _to && (_type == '' || _type == y(this).data('type'))) {
				y(this).show();
			}
		});
	}
});

//_____ Locations.js _____//

define('Locations')
.as(function(y)
{
	var _scope;
	
	var _type = '';
	
	this.start = function(scope)
	{
		_scope = scope
		
		scope.fetch('type').change(function(){
			_type = y(this).val();
			filter();
		});
	}
	
	
	var filter = function() 
	{
		_scope.fetch('location').hide();
		_scope.fetch('location').each(function(){
			if(_type == '' || _type == y(this).data('type')) {
				y(this).show();
			}
		});
	}
});

//_____ Date.js _____//

define('Date')
.use('yellow.View')
.as(function(y, View) {
	
	var _scope;
	var _value;
	var _time;

	
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
		
		moment.locale('en');
		
		var mom = moment(_value);
		_year = mom.year();
		_month = mom.month();
		_day = mom.date();
		_hour = mom.hour();
		_minute = mom.minute();
		_view = View.make(_scope.template('calendar'));
		
		render(_year, _month);
	}
	
	
	var data = function(year, month)
	{
		var mom = moment(year + '-01-01 00:00').add(month, 'M');

		var start = mom.format('d') - 1;
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
				_scope.fetch('day').removeClass('active')
				_scope.fetch('day').addClass('inactive')
				_scope.fetch('day-' + day).removeClass('inactive')
				_scope.fetch('day-' + day).addClass('active')
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
	
	
	
	this.value = function()
	{
		return _value;
	}
	
	
	var update = function()
	{
		
		var mom = moment(_year + '-01-01 00:00')
		.add(_month , 'M')
		.add(_day - 1, 'd')
		.add(_hour, 'h')
		.add(_minute, 'm');
		_value = mom.format('YYYY-MM-DD HH:mm');
		
		_scope.change();
	}
	
});

//_____ Navigation.js _____//

define('Navigation')
.as(function(y)
{
	this.start = function(scope)
	{
				
		scope.fetch('open').click(function(){
			scope.fetch('expanded').show();
		});
		
		scope.fetch('close').click(function(){
			scope.fetch('expanded').hide();
		});
		
		var buttonHeight = scope.fetch('open').offset().top;
		scope.fetch('container').css('margin-top',buttonHeight);
		
	}
});

//_____ timeago.js _____//

define('Timeago')
.as(function(y)
{
	this.start = function(scope)
	{
		var timestamp = new Date().getTime();
		
		var date = (new Date(scope.attr('datetime').split(' ').join('T'))).getTime();
		var day = (24 * 60 * 60 * 1000)*1.5;
		var yesterday = ( timestamp - day );
		var tomorrow = ( timestamp + day );

		if (yesterday < date && date < tomorrow ) {
			scope.timeago();
		} 
	};
});





//_____ Timeago.js _____//

define('Timeago')
.as(function(y)
{
	this.start = function(scope)
	{
		var timestamp = new Date().getTime();
		
		var date = (new Date(scope.attr('datetime').split(' ').join('T'))).getTime();
		var day = (24 * 60 * 60 * 1000)*1.5;
		var yesterday = ( timestamp - day );
		var tomorrow = ( timestamp + day );

		if (yesterday < date && date < tomorrow ) {
			scope.timeago();
		} 
	};
});





//_____ Slider.js _____//

define('Slider')
.as(function(y)
{
	this.start = function(scope)
	{
		if(scope.fetch('slide').length > 1){
			// hide all
			scope.fetch('slide').hide();
			
			// show start slide
			var current = scope.fetch('slide').first();
			current.show();
			
			var counter = 0;
			var hold = true;
			var sliderInterval = setInterval(function(){

				if (!hold){
					counter++;
				}
				if(counter > 200){
					counter = 0;
					// get next
					var next = current.fetch('slide', 'next');
					// if there is no next, use first
					if(next.length <= 0){
						next = scope.fetch('slide').first();
					}
					/*
					// navigation buttons
					scope.fetch('.yf-slide-button-'+current.data('id')).removeClass('active');
					scope.fetch('.yf-slide-button-'+next.data('id')).addClass('active');
					*/
					current.fadeOut();
					next.fadeIn();
					current = next;
				}
			},50);
			
			scope.fetch('next').click(function(){
				// reset counter
				counter = 0;
				// get next
				var next = current.fetch('slide', 'next');
				// if there is no next, use first
				if(next.length <= 0){
					next = scope.fetch('slide').first();
				}
				current.fadeOut();
				next.fadeIn();
				current = next;
				return false;
			});
			
			
			scope.fetch('previous').click(function(){
				// reset counter
				counter = 0;
				// get next
				var next = current.fetch('slide', 'prev');
				// if there is no next, use first
				if(next.length <= 0){
					next = scope.fetch('slide').last();
				}
				current.fadeOut();
				next.fadeIn();
				current = next;
				return false;
			});
			

			scope.fetch('slide').mouseover(function(){
				hold = true;
			});

			scope.fetch('slide').mouseout(function(){
				hold = false;
			});
			
			// r sete inline slider aspect ratio to first slider image aspect ratio
			
			if (scope.data('fixed-size') == true) {
				
				var firstImageHeight = scope.find('img').height();
				var firstImageWidth = scope.find('img').width();
				
				var aspecRatio = firstImageHeight /firstImageWidth ;
				
				var paddingBottom = aspecRatio*100;
				
				scope.css('padding-bottom', paddingBottom+'%');
				scope.css('height', 0);
			}
		} else {
			scope.fetch('navigation').hide();
		}
	}
});

//_____ Search.js _____//

define('Search')
.as(function(y){
	
	var _scope; 
	this.start = function(scope)
	{
		
		_scope = scope;
		
		scope.fetch('search').click(function(){
			if(scope.fetch('query').is(':visible')) {
				submit();
			} else {
				scope.fetch('query').show();
				scope.fetch('query').focus()
			}
		});
		
		scope.fetch('query').keyup(function(e){
			if(e.which == 13){
				submit()
			}
		});
	}
	
	var submit = function()
	{
		var query = _scope.fetch('query').val();
		if(query) {
			var url = _scope.data('url').replace('{{query}}', query);
			document.location.href = url;
		}
	}
});

//_____ Newsletter.js _____//

define('Newsletter')
.as(function(y){
	
	var _scope; 
	this.start = function(scope)
	{
		
		_scope = scope;
		
		scope.fetch('submit').click(function(){
			var email = _scope.fetch('email').val();
			if(email) {
				var url = _scope.data('url');
				y.ajax(url, {
					type: 'POST',
					data: {
						email: email
					},
					dataType: 'json'
				}).done(function(data) {
					if(data.success) {
						scope.fetch('error').hide();
						scope.fetch('email').hide();
						scope.fetch('submit').hide();
						scope.fetch('done').show();
					} else {
						scope.fetch('error').text(data.message).show();
					}
				})
			}
		});
	}
});