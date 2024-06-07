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