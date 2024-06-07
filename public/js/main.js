

//_____ Navigation.js _____//

define("Navigation").as(function(y) {
  this.start = function(scope) {
    scope.fetch("open").click(function() {
      scope.fetch("expanded").show();
      $("body").addClass("nav-expanded");
    });

    scope.fetch("close").click(function() {
      scope.fetch("expanded").hide();
      $("body").removeClass("nav-expanded");
    });

    y(window).on("scroll", function() {
      window.scrollY > scope.offset().top
        ? scope.find(".sticky-nav").addClass("stick-menu")
        : scope.find(".sticky-nav").removeClass("stick-menu");
    });

    /*
		var buttonHeight = scope.fetch('open').offset().top;
		scope.fetch('container').css('margin-top',buttonHeight);
		*/
  };
});


//_____ Main.js _____//

define("Main").as(function (y) {
	this.start = function (scope) {
		console.log(
			"%cThe Moscow Times. Independent News from Russia.",
			"color: #3263c0; font-size: 10px;"
		);

		if (typeof window.freestar === 'object') {
			setTimeout(function () {
				window.freestar.queue.push(function () {
					window.freestar.newPushdown("themoscowtimes.com_pushdown");
				});
			}, 2000);
		}

	};
});


//_____ Search.js _____//

define("Search").as(function(y) {
  const submit = function(scope) {
    const query = scope.fetch("query").val();
    query.length > 0
      ? (document.location.href = scope.data("url") +  '?q=' + encodeURIComponent(query))
      : undefined;
  };

  const showFocus = function(scope, elem) {
    scope.fetch(elem).show();
    scope.fetch(elem).focus();
  };

  this.start = function(scope) {
    scope.fetch("search").on("click", function() {
      scope.fetch("query").is(":visible")
        ? submit(scope)
        : showFocus(scope, "query");
    });

    scope.fetch("query").on("keyup", function(e) {
      e.keyCode === 13
        ? submit(scope)
        : e.keyCode === 27 && scope.fetch("query").is(":visible")
        ? scope.fetch("query").hide()
        : undefined;
    });
  };
});


//_____ Timeago.js _____//

define('Timeago')
.as(function(y) {
	this.start = function(scope) {
		
		
		var timestamp = new Date().getTime();

		var date = new Date(
		  scope.attr('datetime').split(' ').join('T')
		).getTime();

		var day = 24 * 60 * 60 * 1000 * 1.5;
		var yesterday = timestamp - day;
		var tomorrow = timestamp + day;

		if (yesterday < date && date < tomorrow) {		
			scope.timeago();
		}
	}
});


//_____ Newsletter.js _____//

define('Newsletter')
  .as(function(y) {
    var _scope;

    var validateEmail = function(email) {
      var regex = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
      return email.match(regex);
    };

    this.start = function(scope) {
      _scope = scope;
      var active = true;

      scope.fetch("email").on("keyup", function() {
        validateEmail(_scope.fetch("email").val()) ?
          scope.fetch('error').hide() :
          scope.fetch('error').text("Incorrect format").show();
      });

      scope.fetch('submit').click(function() {
        if (active) {
          var email = _scope.fetch('email').val();
          if (validateEmail(email)) {
            scope.fetch('error').hide();
            var url = _scope.data('url');
            active = false;
            y.ajax(url, {
                type: 'POST',
                data: {
                  email: email,
                  name: _scope.fetch('name').val(),
                  //country: _scope.fetch('country').val()
                },
                dataType: 'json'
              })
              .done(function(data) {
                if (data.success) {
                  scope.fetch('error').hide();
                  scope.fetch('email').hide();
                  scope.fetch('name').hide();
                  scope.fetch('submit').hide();
                  scope.fetch('done').show();
                } else {
                  scope
                    .fetch('error')
                    .text(data.message)
                    .show();
                }
              })
              .always(function() {
                active = true;
              });
          } else {
            scope.fetch('error').text("Email is required").show();
          }
        }
      });
    };
  });

//_____ Youtube.js _____//

define("Youtube").as(function(y) {
  this.start = function(scope) {
    var video = scope.data("video");
    scope.fetch("poster").click(function() {
      y(this).hide();
      scope
        .fetch("player")
        .html(
          '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' +
            video +
            '?autoplay=1&loop=1&rel=0&wmode=transparent&mute=1" frameborder="0" allowfullscreen wmode="Opaque"></iframe>'
        )
        .show();
    });
  };
});

// Toggle for homepage videos carousel
define("Videogallery").as(function(y) {
  this.start = function(scope) {
    const toggle = scope.fetch("player-toggle");
    const player = scope.fetch("video-player");

    y(toggle)
      .first()
      .addClass("active");

    toggle.click(function(e) {
      y(this)
        .addClass("active")
        .siblings()
        .removeClass("active");
      player.html(
        '<iframe id="video-' +
          y(this).data("id") +
          '" src="https://www.youtube.com/embed/' +
          y(this).data("id") +
          '?&autoplay=0&loop=0&controls=1&rel=0&wmode=transparent" allowfullscreen="" wmode="Opaque" width="100%" height="100%" frameborder="0"></iframe>'
      );
    });
  };
});


//_____ Slider.js _____//

define("Slider").as(function(y) {
  this.start = function(scope) {
    if (scope.fetch("slide").length > 1) {
      // hide all
      scope.fetch("slide").hide();

      // show start slide
      var current = scope.fetch("slide").first();
      current.show();

      var counter = 0;
      var hold = true;
      var sliderInterval = setInterval(function() {
        if (!hold) {
          counter++;
        }
        if (counter > 200) {
          counter = 0;
          // get next
          var next = current.fetch("slide", "next");
          // if there is no next, use first
          if (next.length <= 0) {
            next = scope.fetch("slide").first();
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
      }, 50);

      scope.fetch("next").click(function() {
        // reset counter
        counter = 0;
        // get next
        var next = current.fetch("slide", "next");
        // if there is no next, use first
        if (next.length <= 0) {
          next = scope.fetch("slide").first();
        }
        current.fadeOut();
        next.fadeIn();
        current = next;
        return false;
      });

      scope.fetch("previous").click(function() {
        // reset counter
        counter = 0;
        // get next
        var next = current.fetch("slide", "prev");
        // if there is no next, use first
        if (next.length <= 0) {
          next = scope.fetch("slide").last();
        }
        current.fadeOut();
        next.fadeIn();
        current = next;
        return false;
      });

      scope.fetch("slide").mouseover(function() {
        hold = true;
      });

      scope.fetch("slide").mouseout(function() {
        hold = false;
      });

      // r sete inline slider aspect ratio to first slider image aspect ratio

      if (scope.data("fixed-size") == true) {
        var firstImageHeight = scope.find("img").height();
        var firstImageWidth = scope.find("img").width();

        var aspecRatio = firstImageHeight / firstImageWidth;

        var paddingBottom = aspecRatio * 100;

        // scope.css("padding-bottom", paddingBottom + "%");
        // scope.css("height", 0);
      } else {
        scope.css("height", current.height());
      }
    } else {
      scope.fetch("navigation").hide();
    }
  };
});


//_____ Image.js _____//

define("Image").as(function(y) {
  this.start = function(scope) {
    var src = scope.data("src");
    if (src) {
      if (!y.isArray(src)) {
        src = [src];
      }
      load(scope[0], src);
    }
  };

  var load = function(image, images) {
    if (images.length > 0) {
      var src = images.shift();
      image.onerror = function() {
        image.onerror = null;
        image.onload = null;
        load(image, images);
      };
      image.onload = function(e) {
        image.onerror = null;
        image.onload = null;
        if (image.naturalHeight <= 90) {
          load(image, images);
        }
      };
      y(image).attr("src", src);
    }
  };
});


//_____ Tabs.js _____//

define("Tabs").as(function(y) {
  this.start = function(scope) {
    scope
      .fetch("tab")
      .click(function() {
        scope.fetch("tab").removeClass(scope.data("active"));
        y(this).addClass(scope.data("active"));
        scope.fetch("content").hide();
        scope.fetch(y(this).data("content")).show();
      })
      .first()
      .click();
  };
});


//_____ Banner.js _____//

define("Banner")
	.use("yellow.Arr")
	.as(function(y, Arr) {
		this.start = function(scope) {
			// all the available banners for this slot
			var pool = scope.data("pool");

			// get the viewports for this slot
			var viewports = scope.data("viewports");

			// get the current viewport
			var viewport = "none";
			y.document.fetch("viewport").each(function() {
				if (y(this).is(":visible")) {
					viewport = y(this).data("viewport");
					return false;
				}
			});

			if (!Arr.has(viewport, viewports)) {
				// no valid viewports: remove entire banner
				scope.fetch("banner", "closest").hide();
				return;
			}

			if (pool.length > 0) {
				pool = shuffle(pool);
				var banner = pool[0];
				if (banner.type === "tag") {
					const bannerHtml = y("<div>" + banner.html + "</div>");
					scope.append(bannerHtml);
					// Load dynamic ads through lazy load
					bannerHtml.start();
				} else if (banner.src) {
					scope.append(
						y(
							'<a href="' +
							banner.href +
							'" target="_blank"><img src="' +
							banner.src +
							'" /></a>'
						)
					);
				}
			}
		};

		var shuffle = function(a) {
			for (var i = a.length - 1; i > 0; i--) {
				var j = Math.floor(Math.random() * (i + 1));
				[a[i], a[j]] = [a[j], a[i]];
			}
			return a;
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

//_____ Events.js _____//

define("Events")
  .use("yellow.View")
  .as(function(y, View) {
    var _scope;

    var _from = 0;
    var _to = 9999999999999999;
    var _type = "";

    this.start = function(scope) {
      var items = scope.data("items");
      var container = scope.fetch("events");
      var view = View.make(scope.template("event"));
      for (var i = 0; i < items.length; i++) {
        var item = view.element(items[i]);
        item.data("dates", items[i].dates);
        item.appendTo(container);

        item.click(function() {
          var more = y(this).fetch("more");
          if (more.length > 0) {
            //container.fetch('excerpt').show();
            //container.fetch('more').hide();
            y(this)
              .fetch("excerpt")
              .hide();
            more.show();
          }
        });
      }

      _scope = scope;

      scope.fetch("day").change(function() {
        switch (y(this).val()) {
          case "today":
            _from = moment()
              .startOf("day")
              .format("X");
            _to = moment()
              .endOf("day")
              .format("X");
            break;
          case "tomorrow":
            _from = moment()
              .add(1, "d")
              .startOf("day")
              .format("X");
            _to = moment()
              .add(1, "d")
              .endOf("day")
              .format("X");
            break;
          case "weekend":
            var day = moment().format("d");
            // set sunday to 7 instead of 0
            day = day == 0 ? 7 : day;
            _from = moment()
              .add(6 - day, "d")
              .startOf("day")
              .format("X");
            _to = moment()
              .add(7 - day, "d")
              .endOf("day")
              .format("X");
            break;
          case "week":
            _from = moment()
              .startOf("week")
              .format("X");
            _to = moment()
              .endOf("week")
              .format("X");
            break;
          case "nextweek":
            _from = moment()
              .add(1, "w")
              .startOf("week")
              .format("X");
            _to = moment()
              .add(1, "w")
              .endOf("week")
              .format("X");
            break;
          case "month":
            _from = moment()
              .startOf("month")
              .format("X");
            _to = moment()
              .endOf("month")
              .format("X");
            break;
          case "nextmonth":
            _from = moment()
              .add(1, "M")
              .startOf("month")
              .format("X");
            _to = moment()
              .add(1, "M")
              .endOf("month")
              .format("X");
            break;
          default:
            _from = 0;
            _to = 9999999999999999;
        }
        filter();
      });

      scope.fetch("date").change(function() {
        var value = y(this).invoke("value");
        _from = moment(value)
          .startOf("day")
          .format("X");
        _to = moment(value)
          .endOf("day")
          .format("X");
        filter();
      });

      scope.fetch("type").change(function() {
        _type = y(this).val();
        filter();
      });
    };

    var filter = function() {
      _scope.fetch("event").hide();
      _scope.fetch("event").each(function() {
        if (
          y(this).data("date") >= _from &&
          y(this).data("date") <= _to &&
          (_type == "" || _type == y(this).data("type"))
        ) {
          y(this).show();
        }
      });
    };
  });


//_____ Date.js _____//

define("Date")
  .use("yellow.View")
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

    this.start = function(scope) {
      _scope = scope;
      _value = scope.data("value");
      _time = scope.data("time");

      moment.locale("en");

      var mom = moment(_value);
      _year = mom.year();
      _month = mom.month();
      _day = mom.date();
      _hour = mom.hour();
      _minute = mom.minute();
      _view = View.make(_scope.template("calendar"));

      render(_year, _month);
    };

    var data = function(year, month) {
      var mom = moment(year + "-01-01 00:00").add(month, "M");

      var start = mom.format("d") - 1;
      var days = mom.endOf("month").format("D");
      var weeks = [];
      var week = [];

      for (var i = 0; i < start; i++) {
        week.push(false);
      }
      for (var day = 1; day <= days; day++) {
        week.push(day);
        if (week.length == 7) {
          weeks.push(week);
          week = [];
        }
      }
      if (week.length > 0) {
        for (var i = week.length; i < 7; i++) {
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
          minute: _minute
        },
        year: year,
        month: mom.format("MMMM"),
        monthnumber: month,
        days: days,
        weeks: weeks,
        active: year == _year && month == _month ? _day : false,
        time: _time
      };
    };

    var render = function(year, month) {
      var calendar = _view.element(data(year, month), {
        previous: function() {
          month--;
          if (month == -1) {
            year--;
            month = 11;
          }
          render(year, month);
        },
        next: function() {
          month++;
          if (month == 12) {
            year++;
            month = 0;
          }
          render(year, month);
        },
        date: function(year, month, day) {
          _year = year;
          _month = month;
          _day = day;
          _scope.fetch("day").removeClass("active");
          _scope.fetch("day").addClass("inactive");
          _scope.fetch("day-" + day).removeClass("inactive");
          _scope.fetch("day-" + day).addClass("active");
          update();
        },
        hourup: function() {
          _hour++;
          if (_hour == 24) {
            _hour = 0;
          }
          update();
        },
        hourdown: function() {
          _hour--;
          if (_hour == -1) {
            _hour = 23;
          }
          update();
        },
        hourchange: function() {
          _hour = Number(
            _scope
              .fetch("hour")
              .val()
              .replace(/[^0-9]+/g, "")
          );
          update();
        },
        minuteup: function() {
          _minute++;
          if (_minute == 60) {
            _minute = 0;
          }
          update();
        },
        minutedown: function() {
          _minute--;
          if (_minute == -1) {
            _minute = 59;
          }
          update();
        },
        minutechange: function() {
          _minute = Number(
            _scope
              .fetch("minute")
              .val()
              .replace(/[^0-9]+/g, "")
          );
          update();
        }
      });
      _scope
        .fetch("container")
        .empty()
        .append(calendar);
    };

    this.value = function() {
      return _value;
    };

    var update = function() {
      var mom = moment(_year + "-01-01 00:00")
        .add(_month, "M")
        .add(_day - 1, "d")
        .add(_hour, "h")
        .add(_minute, "m");
      _value = mom.format("YYYY-MM-DD HH:mm");

      _scope.change();
    };
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

//_____ Locations.js _____//

define("Locations").as(function(y) {
  var _scope;

  var _type = "";

  this.start = function(scope) {
    _scope = scope;

    scope.fetch("type").change(function() {
      _type = y(this).val();
      filter();
    });
  };

  var filter = function() {
    _scope.fetch("location").hide();
    _scope.fetch("location").each(function() {
      if (_type == "" || _type == y(this).data("type")) {
        y(this).show();
      }
    });
  };
});


//_____ Embed.js _____//

define("Embed").as(function(y) {
  this.start = function(scope) {
    // Create video wrapper
    var selectors = [
      "iframe[src*='player.vimeo.com']",
      "iframe[src*='www.youtube.com']",
      "iframe[src*='www.kickstarter.com']",
      "iframe[src*='vk.com']",
      "object",
      "embed"
    ];

    for (var i = 0; i < selectors.length; i++) {
      scope.find(selectors[i]).each(function() {
        var height =
            this.tagName.toLowerCase() == "object"
              ? y(this).attr("height")
              : y(this).height(),
          aspectRatio = height / y(this).width();
        y(this)
          .wrap('<div class="fluid-width-video-wrapper"></div>')
          .parent(".fluid-width-video-wrapper")
          .css("padding-top", aspectRatio * 100 + "%");
        y(this)
          .removeAttr("height")
          .removeAttr("width");
      });
    }

    // create twitter embed
    var html = scope.html().trim();
    if (
      html.indexOf("https://twitter.com") === 0 ||
      html.indexOf("https://www.twitter.com") === 0
    ) {
      y.ajax(
        "https://publish.twitter.com/oembed?url=" + encodeURIComponent(html),
        {
          dataType: "JSONP"
        }
      ).done(function(data) {
        scope.html(data.html);
      });
    }
  };
});


//_____ More.js _____//

define("More").as(function(y) {
	this.start = function(scope) {
		var offset = scope.data("start") || 0;
		var step = scope.data("step") || 18;

		scope.on("click", function() {
			y.ajax(scope.data("url").replace("{{offset}}", offset), {
				dataType: "html"
			}).done(function(html) {
				if (html.trim() == "") {
					scope.hide();
				} else {
					scope.before(y(html));
				}
			});
			offset += step;
		});
	};
});


//_____ contribute/Form.js _____//

define('contribute.Form')
.as(function(y){
	
	var _scope;
	var _period;

	
	this.start = function(scope)
	{
		_scope = scope;
		
		var firstname = scope.fetch('input-firstname');
		var lastname = scope.fetch('input-lastname');
		
		scope.fetch('amount').each(function(){
			var amount = y(this);
			amount.fetch('option').click(function(){
				amount.fetch('option').removeClass('active');
				y(this).addClass('active');
				amount.data('option', y(this).data('amount'));
				if(y(this).data('amount') == 'other') {
					amount.fetch('other').show();
				} else {
					amount.fetch('other').hide();
				}
				label('$');
			})
		})


		
		scope.fetch('period').click(function(){
			scope.fetch('error').remove();
			scope.fetch('period').removeClass('active');
			y(this).addClass('active');
			_period = y(this);
			
			var period = y(this).data('period');
			
			scope.fetch('amount').hide();
			scope.fetch('amount_' + period).show();
			
			if(period == 'once') {
				firstname.fetch('input-wrapper', 'closest').hide();
        lastname.fetch('input-wrapper', 'closest').hide();
			} else {
				firstname.fetch('input-wrapper', 'closest').show();
        lastname.fetch('input-wrapper', 'closest').show();
			}
			label('$');
		});
		
		
		// inital state
		scope.fetch('period_monthly').click();
		scope.fetch('amount_once').fetch('option_50').click();
		scope.fetch('amount_monthly').fetch('option_10').click();
		scope.fetch('amount_annual').fetch('option_50').click();
		
		scope.fetch('other').on('keyup keydown change',function(){
			var val = y(this).val();
			val = val.replace(/[^0-9\,\.]/i, '');
			y(this).val(val);
			label('$');
		});

		scope.fetch('submit').click(function(){
			y.ajax(scope.data('url'), {
				dataType: 'JSON',
				method: 'POST',
				data: {
					amount: getAmount(),
					period: getPeriod(),
					firstname: scope.fetch('input-firstname').val(),
					lastname: scope.fetch('input-lastname').val(),
					email: scope.fetch('input-email').val(),
				}
			}).done(function(data){
				if(data.success && data.url) {
					document.location.href = data.url;
				} else {
					scope.fetch('error').remove();
					for(var key in data.errors) {
						if(key == 'amount') {
							_scope.fetch('amount_' + getPeriod()).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
						} else {
							scope.fetch('input-' + key).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
						}
					}
				}
			});
		});
	}
	
	var label = function(currency)
	{
		var amount = currency + getAmount();
		var period = getPeriod();
		var periods = {
			once: '',
			monthly: 'each month',
			annual: 'each year',
		};
		var analyticsKeys = {
			once: 'once',
			monthly: 'monthly',
			annual: 'annually'
		};

		_scope.fetch('submit').text('Contribute ' + amount + ' ' + periods[period]);
	}
	
	var getAmount = function()
	{
		var period = getPeriod();
		var amount = _scope.fetch('amount_' + period).data('option');
		if(amount == 'other') {
			amount = _scope.fetch('amount_' + period).fetch('other').val();
		} 
		amount = String(amount);
		amount = amount.replace(/[^0-9\,\.]/i, '');
		if(amount.length == 0) {
			return '';
		}
		
		var parts = amount.split(/[\,\.]+/);
		

		var first = parts.shift();
		var last = parts.pop();
		var thousands = first.length <= 3;
		for( var i = 0; i < parts.length; i++) {
			var part = parts[i];
			if(part.length !== 3) {
				thousands = false;
				break;
			}
		}

		if(thousands) {
			amount = first + parts.join('') ;
			if(last && last.length === 3) {
				amount = amount + last;
			} else if(last && last.length > 0) {
				amount = amount + '.' + last.substr(0, 2);
			}
		} else {
			var num = parts.shift();
			if(num) {
				amount = first + '.' + num;
			} else {
				amount = first;
			}
		}
		return amount;
	}
	
	
	var getPeriod = function()
	{
		return _period.data('period');
	}
});

//_____ contribute/Amount.js _____//

define('contribute.Amount')
.as(function(y){
	this.start = function(scope)
	{
		
	}
});

//_____ Contribute/Bar.js _____//

define('contribute.Bar')
  .use('Cookie')
  .as(function(y, Cookie) {
    this.start = function(scope) {
      var windowHeight = y(window).height();
      var scrollTrigger = (windowHeight * 2) / 3;

      y(window).scroll(function() {
        if (Cookie.get('contribute-cta') !== 'suspended') {
          if (y(window).scrollTop() > scrollTrigger) {
            scope.addClass('contribute-bar--show');
          }
        }
      });

      scope.fetch('contributeLater').click(function() {
        Cookie.set('contribute-cta', 'suspended', '3.5');
        scope.removeClass('contribute-bar--show');
      });

    }
  });

//_____ Cookie.js _____//

define("Cookie").as({
  set: function(name, value, days) {
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      var expires = "; expires=" + date.toGMTString();
    } else {
      var expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
  },
  get: function(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == " ") c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  },
  delete: function(name) {
		const arr = document.cookie.split(';')
		const uniq = arr.find(val => val.split('=')[0] === name);
		const cookie = uniq !== undefined && typeof uniq === 'string' ? uniq.split('=')[0] : '';
		this.set(cookie, '', -1);
  }
});


//_____ article/Newsletter.js _____//

define('article.Newsletter')
.use('yellow.View')
.as(function(y, View){

	this.start = function(scope)
	{
		// get content
		var content = scope.fetch('article-content', 'closest');
		
		// move this out of content
		scope.insertAfter(content);
		
		if(
			content.text().replace(/\s+/g, ' ').split(' ').length > 100 
			&& content.find('p').length > 2
		) {
			View.make(scope.template('newsletter')).element()
			.insertBefore(content.find('p').last());
		}
	}
});

//_____ Carousel.js _____//

define("Carousel").as(function(y) {
  this.start = function(scope) {
    var nextBtn = scope.fetch("next");
    var prevBtn = scope.fetch("prev");

    if (scope.fetch("pane").length > 1) {
      // hide all
      scope.fetch("pane").hide();

      // show start slide
      var current = scope.fetch("pane").first();
      current.show();
      var hold = false,
        counter = 0;
      // var clicked = false;

      var intervalID = setInterval(slideOnLoad, 100);

      function carouselToggles(toggleType) {
        return function(e) {
          e.preventDefault();
          var slide = current.fetch("pane", toggleType);
          var type =
            toggleType === "prev"
              ? scope.fetch("pane").last()
              : scope.fetch("pane").first();
          if (slide.length <= 0) slide = type;
          current.fadeOut(0, function() {
            slide.fadeIn(0);
          });
          current = slide;
          if (intervalID && !hold) {
            hold = true;
            return clearInterval(intervalID);
          }
        };
      }

      function slideOnLoad() {
        if (!hold) {
          counter++;
        }
        if (counter > 60) {
          counter = 0;
          // get next
          var next = current.fetch("pane", "next");
          // if there is no next, use first
          if (next.length <= 0) {
            next = scope.fetch("pane").first();
          }
          current.fadeOut(function() {
            next.fadeIn();
          });
          current = next;
        }
      }

      prevBtn.click(carouselToggles("prev"));

      nextBtn.click(carouselToggles("next"));

      /*
			scope.fetch('pane').mouseover(function(){
				hold = true;
			});
			*/
      scope.fetch("pane").click(function() {
        hold = true;
        //clicked = true;
      });

      /*
			scope.fetch('pane').mouseout(function(){
				if(! clicked) {	
					//hold = false;
				}
			});
			*/
      var height = 0;
      scope.fetch("pane").each(function() {
        if (y(this).outerHeight() > height) {
          height = y(this).outerHeight();
        }
      });
      scope.height(height);
    }
  };
});

define('CarouselAmbassadors').as(function(y) {
  this.start = function(scope) {
    const settings = {
      infinite: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      lazyLoad: "progressive",
      autoplay: true,
      autoplaySpeed: 5000,
      arrows: true,
      appendArrows: $(".carousel-toggles-counter"),
      nextArrow: '<a class="slick-next slick-arrow"></a>',
      prevArrow: '<a class="slick-prev slick-arrow"></a>'
    };

    y(scope).on('init', function(e, slider, index) {
      $('.slick-prev').after(
        '<div class="slide-counter"><p class="active-slide-counter">1</p><p>/' +
        slider.slideCount +
        '</p></div>'
      );
    });

    y(scope).slick(settings);

    y(scope).on('afterChange', function(e, slider, cIndex) {
      $('.active-slide-counter').html(cIndex + 1);
    });
  };
});


//_____ Newsletterform.js _____//

define("Newsletterform").as(function(y) {
  this.start = function(scope) {
    var active = true;
    var form = scope.find("form");
    form.submit(function(e) {
      e.stopPropagation();
      e.preventDefault();
      if (active) {
        var data = form.serializeArray();
        active = false;
        y.ajax(scope.data("url"), {
          type: "POST",
          data: data,
          dataType: "json"
        })
          .done(function(data) {
            if (data.success) {
              scope.fetch("error").hide();
              form.hide();
              scope.fetch("done").show();
            } else {
              form.show();
              scope
                .fetch("error")
                .text(data.message)
                .show();
            }
          })
          .always(function() {
            active = true;
          });
      }
    });
  };
});


//_____ contribute/Donate.js _____//

define('contribute.Donate')
	.as(function(y) {

		var _scope;
		var selectedAmount;

		this.start = function(scope) {
			_scope = scope;

			// click amounts
			scope.fetch('amount').click(function() {
				// set label
				scope.fetch('amount').removeClass('active');
				y(this).addClass('active');

				// set current amount
				selectedAmount = y(this).data('amount');

				// show / hide other amount
				if (y(this).data('amount') === 'other') {
					scope.fetch('other').show();
				} else {
					scope.fetch('other').hide();
				}

				// put label on button
				label(scope.attr('data-currency') === 'usd' ? '$' : '€');
			});

			// initial amount
			scope.fetch('amount_' + scope.data('amount')).click();

			// manual amount
			scope.fetch('other').on('keyup keydown change', function() {
				var val = y(this).val();
				val = val.replace(/[^0-9\,\.]/i, '');
				y(this).val(val);
				// put label on button
				label(scope.attr('data-currency') === 'usd' ? '$' : '€');
			});

			// Submit form
			scope.fetch('submit').click(function() {
				y.ajax(scope.data('url'), {
					dataType: 'JSON',
					method: 'POST',
					data: {
						amount: getAmount(),
						period: scope.data('period'),
						firstname: scope.fetch('firstname').val(),
						lastname: scope.fetch('lastname').val(),
						country: scope.fetch('country').val(),
						phone: scope.fetch('phone').val(),
						agree: scope.fetch('agree').is(':checked') ? 1 : 0,
						email: scope.fetch('email').val(),
						currency: scope.attr('data-currency')
					}
				}).done(function(data) {
					if (data.success && data.url) {
						document.location.href = data.url;
					} else {
						scope.fetch('error').remove();
						for (var key in data.errors) {
							if (key === 'amount') {
								_scope.fetch('other').after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
							} else if (key === 'agree') {
								scope.fetch(key).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
							} else {
								scope.fetch(key).after(y('<div class="error" y-name="error">' + data.errors[key] + '</div>'))
							}
						}
					}
				});
			});
		}


		var label = function(currency) {
			var amount = `<span y-name="submit-currency-label">${currency}</span>` + getAmount();
			var periods = {
				once: '',
				monthly: 'each month',
				annual: 'each year',
			};
			_scope.fetch('submit').html('Contribute ' + amount + ' ' + periods[_scope.data('period')]);
		}


		var getAmount = function() {

			var amount = selectedAmount;
			if (amount === 'other') {
				amount = _scope.fetch('other').val();
			}

			amount = String(amount);
			amount = amount.replace(/[^0-9\,\.]/i, '');
			if (amount.length === 0) {
				return '';
			}

			var parts = amount.split(/[\,\.]+/);

			var first = parts.shift();
			var last = parts.pop();
			var thousands = first.length <= 3;
			for (var i = 0; i < parts.length; i++) {
				var part = parts[i];
				if (part.length !== 3) {
					thousands = false;
					break;
				}
			}

			if (thousands) {
				amount = first + parts.join('');
				if (last && last.length === 3) {
					amount = amount + last;
				} else if (last && last.length > 0) {
					amount = amount + '.' + last.substr(0, 2);
				}
			} else if (parts.length > 0) {
				amount = first + '.' + parts.shift().substr(0, 2);
			} else if (last) {
				amount = first + '.' + last.substr(0, 2);
			} else {
				amount = first;
			}
			return amount;
		}
	});

//_____ Timeline.js _____//

define("Timeline").as(function(y) {
  this.start = function(scope) {
    y(window).on("scroll", function() {
      timeLine(scope);
    });
  };

  function timeLine(elem) {
    let start = window.scrollY;
    let end = document.body.offsetHeight - window.innerHeight;
    let position = Math.floor((start / end) * 100);
    return elem.css({ width: position + "%" });
  }
});


//_____ account/Signin.js _____//

define('account.Signin')
.use('Loading')
.use('Cookie')
.as(function(y, Loading, Cookie)
{
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;
		
		scope.fetch('submit').click(function(e){
			e.preventDefault();
			signin();
		});
		
		scope.fetch('identity').keyup(function(e){

			if (e.which == 13) {
				e.preventDefault();
				signin();
			}
		});
	
		scope.fetch('credentials').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				signin();
			}
		});
		
		scope.fetch('recover').click(function(){
			window.location.href = scope.data('recover');
		});
		
		scope.fetch('register').click(function(){
			window.location.href = scope.data('register');
		});
	}
	

	var signin = function()
	{
		_scope.fetch('error').hide();
		
		var identity = _scope.fetch('identity').val();
		var credentials = _scope.fetch('credentials').val();
		//var permanent = _scope.fetch('permanent').is(':checked') ? '1' : '0';
	
		Loading.show();

		y.ajax(_scope.data('signin'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				identity: identity,
				credentials: credentials,
				// permanent: permanent,
			}
		}).done(function(data){
			if(data.success) {
				window.location.href = _scope.data('done');
				// Set cookie for contribute modal
				Cookie.set('contribute-modal', 'suspended', 365);
			} else {
				_scope.fetch('error').text(data.message).show();
			}
		})
		.always(function(){
			Loading.hide();
		});
	}
});

//_____ Loading.js _____//

define('Loading')
.use('yellow.View')
.use('Overlay')
.template('loading' , '<div y-name="__loading__" class="loading" style="' +
	'width:100%; ' +
	'height: 100%;' +
	'background-image: url(\'data:image/gif;base64,R0lGODlhIAAgAPMAANXV1dnZ2dvb297e3t/f3+Dg4OXl5ejo6PDw8PX19fn5+f39/QAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQBCgAAACwAAAAAIAAgAAAE5xDISalZpurNp1pKJQSdVhzVolJDUZRUoCgIpS5T4BIwNSsvyW1CcAl6k8MsMRkCBDskJTFDAZyuAkkqKfxIQ2hhQBFvAYVEIjNBVDW6XNE4MagTiOBAwe60smQUCHd4Rz1ZBQRnFAWDd0hihh12CUE9kjABVlycXIg7AQMGB6SlnJ87paqbSKiKoqusnbMdmDC2tXQlkUhziYtyWTxIfy6BE8WJt5YBvpJivxNaGmLHT0VnOgWYf0dZXS7APdpB309RnHOG5gDqXGLDaC457D1zZ/V/nmOM82XiHQLYKhKP1oZmADdEAAAh+QQBCgAAACwAAAAAIAAgAAAE7hDISWlJperNJ0pIdWRdJRBVolKIopRUcIyUmkyFe8PTfAgTW9B14E0IvuAKcNAZKYYZCiAMuBSkSQAm8G2FTUWot1gYtAUCcBKlVQyKgQReXhQlgoKesAXI5B0DCXULOxMDenoDfTCEWBsBBIlTMAdldx15BWs8CJwlAZ9Po6OJkwGRpnqkqnuSrayqfKmqpLajoiW5HJq7FL1Gr2mMMcKUMIiJgIemy7xZtJsTmsM4xHiKv5KMAXqfyUCJEonXPN2rAOIAmsfB3uPoAK++G+w48edZPK+M6hLJpQo484enXIdQFSS1u6UhksENEQAAIfkEAQoAAAAsAAAAACAAIAAABO4QyEmpMKLqzWcxRkVkXRWQEximx1FSQVEMlDoJrft6cpCCkxxhd5MNJTYAIUekEGQkWyCHkvhKsR7AVmitkIlEYRIbUQZQzeBwLSDCia9AViBcY1WN4A1HVNB0A1cvcAkIRyZPdEQGYV8ccwV5HWxEJ02YmRMKnJ1xAYp0Y5idpQqhopmmCmKgojKasUQHk5BNBwsLOh2RtRq5uQqPZKGIJQMJwAsJf6I0JXMpCsC7kXWDBYNFMxS4C6MAWVWAGYsAdNqW5uaRxkSKJOZKaU3tPOBZ4DuK2LATgJhkPJMgT4KAdFjyPHEnKxFCDhEAACH5BAEKAAAALAAAAAAgACAAAATrEMhJaSCh6s0nKUQlZF0VCFWhUsNaToE6UGoBq+E71SRQeyqUTiLA7VxF0JDyKQh/MVVPMt1EC5lfcjZJ9mIEoaTl1MRIl5o4CUKXOwGyrCIvDKqcWtvadL2SYhyASyNDJ0uIiRMHjI0Gd30/iI2UB5GSS5UHj2l6NoqgOgd4gksFCQkGf0FDqKgInyZ9OX8IrgkIdHpcHQYKXAW2qKpENRg7eAcKCrkTBqixUYFkKAvWAAHLCrFLVxLWCxLKCgmKTULgEwnLB4hJtOkSBdqITT3xEgjLpBtzE/jiuL04REHBAgWhShhYQExHBAAh+QQBCgAAACwAAAAAIAAgAAAE8BDISWkgoerNJylEJWRdFQhVoVLDWk6BOlBqAavhO9UkUHsqlE4iwO1cRdCQ8ikIfzFVTzLdRAuZX3I2SfZiBKGk5dTESJeaOAlClzsBsqwiLwyqnFrb2nS9kmIcgEsjQydLiIlHehhpejaIjzh9eomSjZR+ipslhToCCIRBLwcLCwdDfRgbBQqmpoZ1XBMJrwsJsxsDB2h9YqWmCgZEwhoFBwfDXR89BqaoEwgKCqgJ1gAByAeBVinTChnWCRIE2ooJ09DiEwbIOUMH0+MS60TmS+gKkAD1Eu28S6aFonWNCbcSxyogSDCQU54EynREAAAh+QQBCgAAACwAAAAAIAAgAAAE6BDISWkgoerNJylEJWRdFQhVoVLDWk6BOlBqAavhO9UkUHsqlE4iwO1cRdCQ8ikIfzFVTzLdRAuZX3I2SfYKi8WBFdTESJeaEHAILxQGqrMUaNW4k4R7kcCXaiBVEgYKe0NJaxxtYksjh2NLkZISgDgBhHthkpU4mW6blRiYmZOlh4JWkDqILwYJCXE6TYEbBAivr0N1gH4At7gIiRpFaLNrrq8INgABB70AWxQCH1+vsYMHBzZQPC9VAtkHWUhGkuE5PxJNwiUE4UfLzOlD4WvzAHaoG9nxPi5d+jYUqfAhhykOFwJWiAAAOw==\');' +
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

//_____ Overlay.js _____//

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

//_____ account/Register.js _____//

define('account.Register')
.use('Loading')
.as(function(y, Loading, FB)
{
	var _scope;
	var _email;
	
	this.start = function(scope)
	{
		_scope = scope;
		
		_email = localStorage.getItem('email');
		localStorage.removeItem('email');
		if(_email) {
			scope.fetch('email').val(_email);
		}
		
		scope.fetch('submit').click(register);
		
		scope.fetch('email').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				register();
			}
		});
		
		scope.fetch('password').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				register();
			}
		});
		
		scope.fetch('signin').click(function(){
			window.location.href = scope.data('signin');
		});
	}
	
	
	var register = function()
	{
		_scope.fetch('error').hide();
		
		var email = _scope.fetch('email').val();
		var password = _scope.fetch('password').val();
		var agreed = _scope.fetch('agreed').is(':checked') ? '1' : '0';
		
		if(email && password) {
			Loading.show();
			
			var data = {
				email: email,
				agreed: agreed,
			}
			
			data.password = password;
			
			y.ajax(_scope.data('register'), {
				type: 'POST',
				dataType: 'JSON',
				data: data
			}).done(function(data){
				if(data.success) {
					window.location.href = _scope.data('done');
				} else {
					_scope.fetch('error').text(data.message).show();
				}
			}).always(function(){
				Loading.hide();
			});
		}
	}
});

//_____ account/Confirmation.js _____//

define('account.Confirmation')
.use('Loading')
.as(function(y, Loading)
{
	this.start = function(scope)
	{
		scope.fetch('confirmation').click(function(){
			Loading.show();
			scope.fetch('confirmation').hide();
			scope.fetch('error').hide();
			y.ajax(scope.data('confirmation'), {
				dataType: 'JSON'
			}).done(function(data){
				if(data.success) {
					scope.fetch('sent').show();
				} else {
					scope.fetch('error').text(data.message).show();
				}
			}).always(function(){
				Loading.hide();
			})
		})
	}
});

//_____ account/Confirm.js _____//

define('account.Confirm')
.use('Loading')
.as(function(y, Loading)
{
	this.start = function(scope)
	{
		Loading.show();
		y.ajax(scope.data('customer'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				token: scope.data('token')
			}
		}).done(function(data){
			if(y.isObject(data))
			scope.fetch('input').each(function(){
				var name = y(this).data('name');
				if(name == 'phone_country') {
					if(data.phone && data.phone.country) {
						y(this).val(data.phone.country);
					}
				} else if(name == 'phone_number') {
					if(data.phone && data.phone.number) {
						y(this).val(data.phone.number);
					}
				} else {
					if(data[name]) {
						y(this).val(data[name]);
					}
				}
			})			
		}).always(function(){
			Loading.hide();
		})
		
		

		scope.fetch('submit').click(function(){
			Loading.show();
			var data = {
				token: scope.data('token'),
				csrf: scope.data('csrf'),
			}
			
			// get filled in variables
			scope.fetch('input').each(function(){
				var val = y(this).val()
				if(val) {
					data[y(this).data('name')] = val;
				}
			})

			y.ajax(scope.data('confirm'), {
				type: 'POST',
				dataType: 'JSON',
				data: data
			}).done(function(data){
				if(data.success) {
					window.location.href = scope.data('done');
				} else {
					scope.fetch('error').text(data.message).show();
				}
			}).fail(function(){
				scope.fetch('error').text('Unable to store your information').show();
			}).always(function(){
				Loading.hide();
			});
		});
	}
});

//_____ account/Signout.js _____//

define('account.Signout')
.use('Loading')
.as(function(y, Loading)
{
	this.start = function(scope)
	{
		scope.click(function(){
			Loading.show();
			y.ajax(_scope.data('signout'), {
				dataType: 'JSON',
			}).done(function(data){
				if(data.success) {
					window.location.href = _scope.data('done');
				}
			}).always(function(){
				Loading.hide();
			});
		})
	}
});

//_____ account/Recover.js _____//

define('account.Recover')
.use('Loading')
.as(function(y, Loading)
{
	
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;
		scope.fetch('submit').click(recover);
		
		scope.fetch('email').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				recover();
			}
		});
		scope.fetch('signin').click(function(){
			window.location.href = scope.data('signin');
		});
	}
	

	var recover = function()
	{
		_scope.fetch('error').hide();
		
		var email = _scope.fetch('email').val();

		Loading.show();

		y.ajax(_scope.data('recover'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				email: email,
			}
		}).done(function(data){
			if(data.success) {
				_scope.fetch('done').show();
				_scope.fetch('form').hide();
			} else {
				_scope.fetch('error').text(data.message).show();
			}
		}).always(function(){
			Loading.hide();
		});
	}
});

//_____ account/Reset.js _____//

define('account.Reset')
.use('Loading')
.as(function(y, Loading)
{
	
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;
		scope.fetch('submit').click(reset);
		
		scope.fetch('email').keyup(function(e){
			if (e.which == 13) {
				e.preventDefault();
				reset();
			}
		});
		
		scope.fetch('signin').click(function(){
			window.location.href = scope.data('signin');
		});
	}
	

	var reset = function()
	{
		_scope.fetch('error').hide();
		
		var password = _scope.fetch('password').val();

		Loading.show();

		y.ajax(_scope.data('reset'), {
			type: 'POST',
			dataType: 'JSON',
			data: {
				password: password,
				token: _scope.data('token'),
				csrf: _scope.data('csrf'),
			}
		}).done(function(data){
			if(data.success) {
				_scope.fetch('done').show();
				_scope.fetch('form').hide();
			} else {
				_scope.fetch('error').text(data.message).show();
			}
		}).always(function(){
			Loading.hide();
		});
	}
});

//_____ account/Dashboard.js _____//

define('account.Dashboard')
.use('yellow.View')
.use('Loading')
.use('Dialog')
.use('Cookie')
.as(function(y, View, Loading, Dialog, Cookie)
{
	
	var _scope;
	
	this.start = function(scope)
	{
		_scope = scope;

		// Signout button(s)
		scope.fetch('signout').click(signout);
		
		// Sidemenu
		scope.fetch('sidemenu-expand').click(function(){
			scope.fetch('sidemenu-overlay').toggle()
			scope.fetch('sidemenu').toggle();
		})
		scope.fetch('sidemenu-overlay').click(function(){
			scope.fetch('sidemenu-overlay').hide()
			scope.fetch('sidemenu').hide();
		})
		scope.fetch('sidemenu-close').click(function(){
			scope.fetch('sidemenu-overlay').hide()
			scope.fetch('sidemenu').hide();
		})

		
		// Get account data
		Loading.show();
		y.ajax(scope.data('account'), {
			dataType: 'json'
		}).done(function(data){
			// Build top menu
			menu(data);
			// Make sidemenu clickable
			scope.fetch('sidemenu-section').click(function(){
				scope.fetch('sidemenu-link').removeClass('sidemenu__link--active');
				y(this).fetch('sidemenu-link').addClass('sidemenu__link--active');
				switch(y(this).data('section')) {
					case 'account':
						account(data);
						break;
					case 'donations':
						Loading.show();
						y.ajax(scope.data('donations'), {
							dataType: 'json'
						}).done(function(donationsData){
							donations(donationsData);
						}).fail(function(data){
							document.location.reload()
						}).always(function(){
							Loading.hide();
						})
						break;
				}
			})
			// Build account section
			account(data);
		}).always(function(){
			Loading.hide();
		})
	}
	
	
	/*
	 * Create header menu
	 */
	var menu = function(data)
	{
		// Create top menu
		var menu = View.make(_scope.template('menu'))
		.element({
			name: data.name || ''
		}, {
			signout: signout
		});

		menu.fetch('expand').click(function(e){
			e.stopPropagation();
			e.preventDefault();
			menu.fetch('options').toggle();
		})
		y(document).click(function(){
			menu.fetch('options').hide();
		})
		_scope.fetch('header').append(menu);

	}
	
	
	/*
	 * Create the account sections
	 */
	var account = function(data)
	{
		var content = View.make(_scope.template('account'), {}, {
			format: function(date) {
				var d = new Date(date);
				if(date && ! isNaN(d.getMonth())) {
					return d.toLocaleDateString('en-GB');
				} else {
					return '';
				}
			}
		})
		.element(data, {
			password: function(){
				content.fetch('password').hide();
				content.fetch('password-update').show();
			},
			passwordUpdate: function(){
				content.fetch('password-update').fetch('error').hide();
				update({
					password: content.fetch('password-update').fetch('value').val()
				}, function(message){
					Dialog.alert(message);
					account(data);
				}, function(error){
					content.fetch('password-update').fetch('error').text(error).show();
				})
			},
			passwordCancel: function(){
				content.fetch('password').show();
				content.fetch('password-update').fetch('error').hide();
				content.fetch('password-update').fetch('value').val('');
				content.fetch('password-update').hide();
			},
			information: function(){
				content.fetch('information').hide();
				content.fetch('information-update').fetch('input').each(function(){
					var name = y(this).data('name');
					if(data[name]) {
						 y(this).val(data[name]);
					}
				})
				content.fetch('information-update').show();
			},
			informationUpdate: function(){
				var values = {};
				content.fetch('information-update').fetch('input').each(function(){
					var val = y(this).val()
					if(val) {
						values[y(this).data('name')] = val;
					}
				})
				update(values, function(message){
					Dialog.alert(message);
					for(var key in values) {
						data[key] = values[key]
					}
					account(data);
				}, function(error){
					content.fetch('information-update').fetch('error').text(error).show();
				})
			},
			informationCancel: function(){
				content.fetch('information').show();
				content.fetch('information-update').fetch('error').hide();
				content.fetch('information-update').fetch('input').val('');
				content.fetch('information-update').hide();
			},
			/*
			email: function(){
				content.fetch('email').hide();
				content.fetch('email-update').show();
				
			},
			emailUpdate: function(){
				update({
					email: content.fetch('email-update').fetch('value').val()
				}, function(message){
					Dialog.alert(message);
					data.email = content.fetch('email-update').fetch('value').val();
					account(data);
				}, function(error){
					content.fetch('email-update').fetch('error').text(error).show();
				})
			},
			emailCancel: function(){
				content.fetch('email').show();
				content.fetch('email-update').fetch('error').hide();
				content.fetch('email-update').fetch('value').val('');
				content.fetch('email-update').hide();
			},
			*/
			signoff: function() {
				Dialog.alert('We hate to see you go! <br />Send an e-mail to development@themoscowtimes.com, to remove your account')
			}
		});
		
		_scope.fetch('content')
		.empty()
		.append(content);
	}
	
	
	var donations = function(data)
	{
		// Create panel
		var content = View.make(_scope.template('donations'), {}, {
			format: function(date) {
				var d = new Date(date);
				if(date && ! isNaN(d.getMonth())) {
					return d.toLocaleDateString('en-GB');
				} else {
					return '';
				}
			}
		}).element({donations: data},{
			donate: function() {
				document.location.href = _scope.data('donate');
			},
			donationCancel: function(){
				var url = y(this).data('url');
				var donation = y(this).fetch('donation', 'closest');
				Dialog.confirm('Confirm cancellation', 'Are you sure you want to stop your recurring donation to The Moscow Times', function(){
					Loading.show();
					y.ajax(url, {
						dataType: 'JSON',
						type: 'POST',
						data: {
							csrf: _scope.data('csrf')
						}
					}).done(function(response){
						y.ajax(_scope.data('donations'), {
							dataType: 'json'
						}).done(function(donationsData){
							donations(donationsData);
						}).fail(function(){
							document.location.reload();
						}).always(function(){
							Dialog.alert('Your recurring donation was cancelled');
							Loading.hide();
						})
					}).fail(function(){
						Loading.hide();
						Dialog.alert('Unable to cancel your donation. Please try again at a later time.');
					})
				})
			},
			donationUpdate: function(){
				var donation = y(this).fetch('donation', 'closest');
				donation.fetch('donation-info').hide();
				donation.fetch('donation-update').show();
			},
			donationUpdateCancel: function(){
				var donation = y(this).fetch('donation', 'closest');
				donation.fetch('donation-info').show();
				donation.fetch('donation-update').hide();
			},
			donationUpdateUpdate: function(){
				var donation = y(this).fetch('donation', 'closest');
				var url = y(this).data('url');
				var amount = String(donation.fetch('value').val());
				amount = amount.replace(/[^0-9\,\.]/i, '');
				if(amount.length == 0) {
					return;
				}
				var parts = amount.split(/[\,\.]+/);
				var first = parts.shift();
				var last = parts.pop();
				var thousands = first.length <= 3;
				for( var i = 0; i < parts.length; i++) {
					var part = parts[i];
					if(part.length !== 3) {
						thousands = false;
						break;
					}
				}
				if(thousands) {
					amount = first + parts.join('') ;
					if(last && last.length === 3) {
						amount = amount + last;
					} else if(last && last.length > 0) {
						amount = amount + '.' + last.substr(0, 2);
					}
				} else if(parts.length > 0) {
					amount = first + '.' + parts.shift().substr(0, 2);
				} else if(last) {
					amount = first + '.' + last.substr(0, 2);
				} else {
					amount = first;
				}

				Dialog.confirm('Confirm new donation amount', 'Are you sure you want to change your recurring donation amount to $' + amount + ' ?', function(){
					Loading.show();
					y.ajax(url, {
						dataType: 'JSON',
						type: 'POST',
						data: {
							csrf: _scope.data('csrf'),
							amount: amount
						}
					}).done(function(response){
						if(response.success) {
							y.ajax(_scope.data('donations'), {
								dataType: 'json'
							}).done(function(donationsData){
								donations(donationsData);
							}).fail(function(){
								document.location.reload();
							}).always(function(){
								Dialog.alert('Your recurring donation was updated');
								Loading.hide();
							})
						} else {
							Loading.hide();
							Dialog.alert(response.message);
						}
					}).fail(function(){
						Loading.hide();
						Dialog.alert('Unable to update your donation. Please try again at a later time.');
					})
				})
			},
		});
		
		// Add to screen
		_scope.fetch('content')
		.empty()
		.append(content);
	}
	
	
	/**
	 * Update account data
	 */
	var update = function(data, success, fail)
	{
		data.csrf = _scope.data('csrf');
		Loading.show();
		y.ajax(_scope.data('update'), {
			dataType: 'JSON',
			type: 'POST',
			data: data
		}).done(function(data){
			if(data.success) {
				success(data.message);
			} else {
				fail(data.message);
			}
		}).fail(function(){
			fail('Unable to update your information. Please try again at a later time.');
		}).always(function(){
			Loading.hide();
		});
	}
	
	
	/*
	 * Sign out and redirect to signin
	 */
	var signout = function()
	{
		Cookie.delete('contribute-modal');
		Loading.show();
		y.ajax(_scope.data('signout'), {
			dataType: 'JSON',
		}).done(function(data){
			if(data.success) {
				window.location.href = _scope.data('signin');
			}
		}).always(function(){
			Loading.hide();
		});
	}
});

//_____ Dialog.js _____//

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

//_____ Account.js _____//

define('Account')
	.as(function(y) {
		this.start = function(scope) {
			var letter = scope.data('letter') || 'T';

			scope.fetch('account').toggle();

			scope.fetch('letter')
				.text(letter.toUpperCase())
				.click(function(e) {
					e.stopPropagation();
					scope.fetch('menu').toggle();
				});

			y('body').click(function() {
				scope.fetch('menu').hide()
			});

			scope.fetch('signin').hide();
		}
	});

//_____ contribute/Done.js _____//

define('contribute.Done')
.use('Cookie')
.as(function(y,Cookie){
	this.start = function(scope)
	{
		Cookie.set('contribute-cta', 'suspended', '31');
	}
});

//_____ Copy2Clipboard.js _____//

define("Copy2Clipboard").as(function(y) {
  this.start = function(scope) {
    const url = document.location.href;
    scope.fetch("copy").on("click", function(e) {
      e.preventDefault();
      cp2clip(scope, url, true);
    });
  };

  function cp2clip(scope, val, showMessage) {
    const tempInput = y("<input>");
    y("body").append(tempInput);
    tempInput.val(val).select();
    document.execCommand("copy");
    tempInput.remove();
    if (typeof showMessage === "undefined") {
      showMessage = true;
    }
    notice(scope.fetch("to_copy"), scope.fetch("copied"));
  }

  function notice(from, to) {
    from.fadeOut("slow", () => {
      to.fadeIn("slow", () => {
        setTimeout(() => {
          to.hide();
          from.fadeIn("slow");
        }, 1000);
      });
    });
  }
});


//_____ contribute/Tabs.js _____//

define("contribute.Tabs").as(function (y) {
	function updateCurr(arr, label) {
		return arr.map(function (val) {
			y(val).text(label);
		});
	}

	this.start = function (scope) {
		// Convert array
		const arr = $.map(scope.fetch("curr-label"), function (value) {
			return [value];
		});

		updateCurr(arr, "$");

		scope.fetch("tab").click(function () {
			scope.fetch("tab").removeClass(scope.data("active"));
			y(this).addClass(scope.data("active"));
			scope.fetch("content").hide();
			scope.fetch(y(this).data("content")).show();
		});

		// Select currency and set on form data attribute
		scope.fetch("currency-selector").on("change", function (e) {
			let label = y(this).val() === "usd" ? "$" : "€";
			scope.fetch("donate-form").attr("data-currency", y(this).val());
			updateCurr(arr, label);
			$.map(scope.fetch("submit-currency-label"), function (val) {
				return y(val).html(label);
			});
		});
		
		const zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		if(zone.indexOf('Europe/') === 0) {
			scope.fetch("currency-selector").val('eur')
			scope.fetch("currency-selector").change()
		}
	};
});


//_____ article/IsIntersecting.js _____//

define("article.IsIntersecting")
	.as(function(y) {
		this.start = function(scope) {
			const observer = new IntersectionObserver(
				entries => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const { articleUrl, articleTitle } = y(entry.target)
								.children(".article-container")
								.data();
							history.pushState(
								y(entry.target)
								.children(".article-container")
								.data(),
								articleTitle,
								articleUrl
							);
							document.title = `${articleTitle} - The Moscow Times`;
						}
					});
				}, {
					root: null,
					rootMargin: "0px",
					threshold: 0
				}
			);
			observer.observe(scope[0]);
		};
	});

//_____ article/InfiniteScroll.js _____//

define("article.InfiniteScroll")
  .use("IntObserver")
  .as(function(y, IntObserver) {
    this.start = function(scope) {
      const getArticle = (observer, targetElem) => {
        const { url, id } = y(targetElem).data();

        y.ajax({
            type: "get",
            async: true,
            dataType: "html",
            url: url.replace("{{id}}", id)
          })
          .done((html, status) => {
            y(targetElem).css("visibility", "visible");
            const child = y(
              `<article y-use="article.IsIntersecting">${html}</article>`
            );
            y("#load-next-article").before(child);
            child.start();

            const { pageId, nextId, articleUrl, articleTitle } = y(
              `#article-id-${id}`
            ).data();

            y(targetElem)
              .data("id", nextId)
              .attr("data-id", nextId);

            const historyStateObj = {
              pageId,
              nextId,
              articleUrl,
              articleTitle
            };

            document.title = `${historyStateObj.articleTitle} - The Moscow Times`;

            history.pushState(historyStateObj, "", articleUrl);

            if (typeof nextId === "string" && nextId.length === 0) {
              y(targetElem).css("visibility", "hidden");
              observer.unobserve(scope[0]);
            }
          })
          .fail(err => y(targetElem).css("visibility", "hidden"));
      };

      IntObserver.init(
        scope[0], {
          root: null,
          rootMargin: "0px",
          threshold: 0
        },
        getArticle,
        scope[0]
      );
    };
  });

//_____ IntObserver.js _____//

define("IntObserver").as({
  init: (targetElem, options, fn, ...args) => {
    const callback = (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          return fn(observer, ...args);
        }
      });
    };
    const observer = new IntersectionObserver(callback, options);
    observer.observe(targetElem);
  }
});


//_____ contribute/Modal.js _____//

define("contribute.Modal")
	.use("Cookie")
	.as(function (y, Cookie) {
		this.start = function (scope) {
			setTimeout(() => {
				if (Cookie.get("contribute-modal") != "suspended") {
					y(scope)
						.css("display", "flex")
						.hide()
						.fadeIn("slow")
						.parent()
						.css("overflow", "hidden");

					scope.fetch("close").on("click", function (e) {
						y(scope).fadeOut("slow");
						Cookie.set("contribute-modal", "suspended", 1);
						y(scope).parent().css("overflow", "scroll");
					});
					// Close on ESC
					y("body").on("keyup", function (e) {
						if (e.keyCode === 27) {
							y(scope).fadeOut("slow");
							Cookie.set("contribute-modal", "suspended", 1);
							y(scope).parent().css("overflow", "scroll");
						}
					});
					// On proceed button click
					y(scope)
						.fetch("contribute-btn")
						.on("click", function () {
							y(scope).fadeOut("slow");
							Cookie.set("contribute-modal", "suspended", 3);
							y(scope).parent().css("overflow", "scroll");
						});
				} else {
					y(scope).hide();
				}
			}, 10000);
		};
	});


//_____ home/CollectionCarousel.js _____//

define("home.CollectionCarousel")
	.as(function (y) {
		this.start = function (scope) {
			const settings = {
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				lazyLoad: "progressive",
				autoplay: true,
				autoplaySpeed: 10000,
				arrows: true,
				appendArrows: "",
				prevArrow: "",
				nextArrow: "",
			};

			const settingsMobile = {
				...settings,
				arrows: false,
			};

			const carousel = y(scope).fetch("lead-carousel");

			const condition = y(this).hasClass("mobile-carousel")
				? settingsMobile
				: settings;

			carousel.on("init", function () {
				const slide = carousel.fetch("lead-carousel-slide");
				slide.removeClass("lead-carousel-slide");
			});

			carousel.slick(condition);

			carousel.on("beforeChange", function (slick, currSlide) {
				const slide = y(currSlide.$slides.get(currSlide.currentSlide));
				const { url, title } = slide.find("figure").data();
				// Parent Carousel Data
				const { collection, collectionUrl } = carousel.data();
			});
		};
	});


//_____ Live.js _____//

define("Live")
	.use("yellow.View")
	.as(function (y, View) {
		var _ids = {};
		var _from = 0;
		var _scope;

		this.start = function (scope) {
			_scope = scope;

			setInterval(function () {
				load();
			}, 10 * 1000);

			// preload(scope, 3);

			load();

		};

		var chunkArr = function (arr, limit) {
			return arr.reduce(function (acc, curr, idx) {
				var chunk = idx % limit;
				chunk ? acc[acc.length - 1].push(curr) : acc.push([curr]);
				return acc;
			}, []);
		};

		var lazyLoad = function (elem, posts, oldPosts, scope, limit) {
			var rArr = posts.reverse();
			var arr = chunkArr(rArr, limit);
			var prevArr = chunkArr(oldPosts, limit)
			var intersectionCounter = 0;
			arr.length > 0 && scope.prepend(arr[0]);
			prevArr.length > 0 && scope.append(prevArr[0]);

			var observer = new IntersectionObserver(
				function (entries) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							intersectionCounter++;
							arr.map(function (post, idx) {
								intersectionCounter === idx && _scope.append(post);
								y("#load-next").css(
									"visibility",
									intersectionCounter === idx ? "visible" : "hidden"
								);
							});
						}
					});
				},
				{
					root: null,
					rootMargin: "0px",
					threshold: 0,
				}
			);

			observer.observe(elem);
		};

		var preload = function (scope, limit) {
			// Get previous url
			// const splitUrl = _scope.data("url").split("/");
			// const id = splitUrl[5];
			// const index = splitUrl.indexOf(id);
			// index !== -1 ? (splitUrl[index] = id - 1) : "";
			// const url = splitUrl.join("/");
			const oldPostView = View.make(
				_scope.template("post"),
				{},
				{
					time: function (time) {
						var months = [
							"Jan.",
							"Feb.",
							"March",
							"April",
							"May",
							"June",
							"July",
							"Aug.",
							"Sept.",
							"Oct.",
							"Nov.",
							" Dec.",
						];
						var date = new Date(time);
						var hours = date.getHours();
						var ampm = hours >= 12 ? "PM" : "AM";
						hours = hours % 12;
						hours = hours ? hours : 12;
						return (
							months[date.getMonth()] +
							" " +
							date.getDate() +
							", " +
							date.getFullYear() +
							" - " +
							hours +
							":" +
							String(date.getMinutes()).padStart(2, "0") +
							" " +
							ampm
						);
					},
				}
			);
			const oldBlockHtmlView = View.make(_scope.template("block-html"));
			const oldBlockArticleView = View.make(_scope.template("block-article"));
			const oldBlockLinkView = View.make(_scope.template("block-link"));
			const oldBlockImageView = View.make(_scope.template("block-image"));
			const oldBlockEmbedView = View.make(_scope.template("block-embed"));

			console.log(scope.data('prevurl'));

			y.ajax(scope.data('prevurl').replace("{{from}}", 100), {
				dataType: "json",
			}).done(function (items) {
				let oldPosts = [];
				for (let idx = 0; idx < limit; idx++) {
					let item = items[idx];
					const oldPost = oldPostView.element(item);
					oldPosts = [oldPost, ...oldPosts];
					const oldMount = oldPost.fetch("body");

					for (let k = 0; k < item.body.length; k++) {
						const oldBlock = item.body[k];
						let oldElement = undefined;
						if (oldBlock.type == "html" && oldBlock.body) {
							oldElement = oldBlockHtmlView.element();
							oldElement.append(y(oldBlock.body));
						} else if (oldBlock.type == "article" && oldBlock.article) {
							oldElement = oldBlockArticleView.element(oldBlock.article);
						} else if (oldBlock.type == "image" && oldBlock.image) {
							oldElement = oldBlockImageView.element(oldBlock.image);
						} else if (oldBlock.type == "link" && oldBlock.link) {
							oldElement = oldBlockLinkView.element(oldBlock);
						} else if (oldBlock.type == "embed" && oldBlock.embed) {
							oldElement = y(oldBlockEmbedView.render());
							oldElement.fetch("embed").append(y(oldBlock.embed));
						}

						if (oldElement) {
							oldMount.append(oldElement);
							if (oldBlock.type == "embed") {
								oldElement.start();
							}
						}
					}

				}
				lazyLoad(y("#load-next")[0], [], oldPosts, scope, 3);

			});
		};

		var load = function () {
			var active = _from > 0;
			var postView = View.make(
				_scope.template("post"),
				{},
				{
					time: function (time) {
						var months = [
							"Jan.",
							"Feb.",
							"March",
							"April",
							"May",
							"June",
							"July",
							"Aug.",
							"Sept.",
							"Oct.",
							"Nov.",
							" Dec.",
						];
						var date = new Date(time);
						var hours = date.getHours();
						var ampm = hours >= 12 ? "PM" : "AM";
						hours = hours % 12;
						hours = hours ? hours : 12;
						return (
							months[date.getMonth()] +
							" " +
							date.getDate() +
							", " +
							date.getFullYear() +
							" - " +
							hours +
							":" +
							String(date.getMinutes()).padStart(2, "0") +
							" " +
							ampm
						);
					},
				}
			);
			var blockHtmlView = View.make(_scope.template("block-html"));
			var blockArticleView = View.make(_scope.template("block-article"));
			var blockLinkView = View.make(_scope.template("block-link"));
			var blockImageView = View.make(_scope.template("block-image"));
			var blockEmbedView = View.make(_scope.template("block-embed"));
			var from = Math.floor(_from / 60) * 60;

			y.ajax(_scope.data("url").replace("{{from}}", from), {
				dataType: "json",
			}).done(function (items) {
				var posts = [];
				for (var i = 0; i < items.length; i++) {
					var item = items[i];
					if (!_ids[item.id]) {
						_ids[item.id] = true;
						_from = new Date(item.time).getTime() / 1000;
						var post = postView.element(item);
						if (active) {
							// Push on new post
							// since it won't lazy load
							posts = [post, ...posts];
							post.addClass("live-post--new");
						}
						posts.push(post);

						var mount = post.fetch("body");
						for (var j = 0; j < item.body.length; j++) {
							var block = item.body[j];
							var element = null;
							if (block.type == "html" && block.body) {
								element = blockHtmlView.element();
								element.append(y(block.body));
							} else if (block.type == "article" && block.article) {
								element = blockArticleView.element(block.article);
							} else if (block.type == "image" && block.image) {
								element = blockImageView.element(block.image);
							} else if (block.type == "link" && block.link) {
								element = blockLinkView.element(block);
							} else if (block.type == "embed" && block.embed) {
								element = y(blockEmbedView.render());
								element.fetch("embed").append(y(block.embed));
							}

							if (element) {
								mount.append(element);
								if (block.type == "embed") {
									element.start();
								}
							}
						}
					}
				}
				lazyLoad(y("#load-next")[0], posts, [], _scope, 20);
			});
		};
	});


//_____ ProgressBar.js _____//

define("ProgressBar").as(function(y) {
  this.start = function(scope) {
    y(window).on("scroll", function() {
      timeLine(scope);
    });
  };
  function timeLine(elem) {
    let start = window.scrollY;
    let end = document.body.offsetHeight - window.innerHeight;
    let position = Math.floor((start / end) * 100);
    return elem.css({ width: position + "%" });
  }
});


//_____ home/SidebarCarousel.js _____//

// TODO: GA onScroll
define("home.SidebarCarousel")
	.as(function (y) {
		this.start = function (scope) {
			const settings = {
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				lazyLoad: "progressive",
				autoplay: true,
				autoplaySpeed: 5000,
				arrows: true,
				appendArrows: y(".carousel-sidebar"),
				prevArrow:
					'<a class="carousel__prev carousel__arrow" y-name="arrow"><svg viewBox="0 0 256 256"><polyline fill="none" stroke="white" stroke-width="20" stroke-linejoin="round" stroke-linecap="round" points="184,16 72,128 184,240" /></svg></a>',
				nextArrow:
					'<a class="carousel__next carousel__arrow" y-name="arrow"><svg viewBox="0 0 256 256"><polyline fill="none" stroke="white" stroke-width="20" stroke-linejoin="round" stroke-linecap="round" points="72,16 184,128 72,240" /></svg></a>',
			};

			const settingsMobile = {
				...settings,
				arrows: false,
				// dots: true,
			};

			y(scope).each(function (i, elem) {
				const condition = y(this).hasClass("mobile-carousel")
					? settingsMobile
					: settings;
				y(this)
					.slick(condition)
					.hover(
						function () {
							scope.fetch("arrow").css("visibility", "visible");
						},
						function () {
							scope.fetch("arrow").css("visibility", "hidden");
						}
					);
				y(this).on("beforeChange", function (slick, currSlide) {
					const slide = y(currSlide.$slides.get(currSlide.currentSlide));
					const { title } = slide.fetch("carousel-pane").data();
				});
			});
		};
	});


//_____ article/StickyBanner.js _____//

define('article.StickyBanner')
	.as(function(y) {
		this.start = function(scope) {
			y(scope).fetch('close').on('click', function (e) {
				e.preventDefault();
				y(scope).hide();
			});
		};
	});


//_____ Newsletters.js _____//

define("Newsletters").as(function(y) {
	var _scope;

	var validateEmail = function(email) {
		var regex = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
		return email.match(regex);
	};

	this.start = function(scope) {
		_scope = scope;
		var active = true;
		var checkbox = scope.fetch("checkbox");
		// var form = scope.fetch("form");
		var tags = {
			newsletter: 1,
			newsletterBell: 0,
		};

		// var theBellCheckbox = scope.find("#weekly-bell");

		var url = new URL(window.location);

		// Select the newsetter, if params
		/*
		if (url.searchParams.get("letter") === "russianmoney") {
			theBellCheckbox
				.attr("checked", true)
				.parents(".newsletters__block")
				.addClass("selected");
			tags = {
				...tags,
				newsletterBell: 1,
			};
		}
		*/
		// Fill in email, if param
		scope
			.fetch("email")
			.val(
				url.searchParams.get("email") !== null ?
				url.searchParams.get("email") :
				""
			);

		var err = scope.fetch("error");

		/*
		checkbox.on("click", function() {
			y(this)
				.parents(".newsletters__block")
				.toggleClass(y(this).is(":checked") ? "selected" : "selected");

			tags = {
				...tags,
				[y(this).data("tag")]: y(this).is(":checked") ? 1 : 0,
			};
		});
		*/

		scope.fetch("email").on("keyup", function() {
			validateEmail(_scope.fetch("email").val()) ?
				err.hide() :
				err.text("Incorrect email format").show();
		});

		scope.fetch("submit").click(function() {
			if (active) {
				var email = _scope.fetch("email").val();
				if (validateEmail(email)) {
					err.hide();
					var url = _scope.fetch("url").data("url");
					active = false;
					y.ajax(url, {
							type: "POST",
							data: {
								email: email,
								name: _scope.fetch("name").val(),
								tags,
							},
							dataType: "json",
						})
						.done(function(data) {
							if (data.success) {
								scope.fetch("error").hide();
								scope.fetch("email").hide();
								scope.fetch("name").hide();
								scope.fetch("submit").hide();
								scope.fetch("done").show();
							} else {
								scope.fetch("error").text(data.message).show();
							}
						})
						.always(function() {
							active = true;
						});
				} else {
					err.text("Email is required").show();
				}
			}
		});
	};
});

//_____ contribute/Bar.js _____//

define('contribute.Bar')
  .use('Cookie')
  .as(function(y, Cookie) {
    this.start = function(scope) {
      var windowHeight = y(window).height();
      var scrollTrigger = (windowHeight * 2) / 3;

      y(window).scroll(function() {
        if (Cookie.get('contribute-cta') !== 'suspended') {
          if (y(window).scrollTop() > scrollTrigger) {
            scope.addClass('contribute-bar--show');
          }
        }
      });

      scope.fetch('contributeLater').click(function() {
        Cookie.set('contribute-cta', 'suspended', '3.5');
        scope.removeClass('contribute-bar--show');
      });

    }
  });

//_____ Scrollama.js _____//

// For graph interactions
// https://russellgoldenberg.github.io/scrollama/sticky-overlay/

define("Scrollama").as(function(y) {
  this.start = function(scope) {
    const figure = scope.fetch("figure");
    const article = scope.fetch("article");
    const step = scope.fetch("step");
    const scroller = scrollama();
    initScroller(scroller, step, figure, scope);
  };

  // generic window resize listener event
  function handleResize(scroller, step, figure) {
    let stepH = Math.floor(window.innerHeight * .75);
    step.css({ height: stepH });

    let figureHeight = window.innerHeight / 2;
    let figureMarginTop = (window.innerHeight - figureHeight) / 5;

    figure.css({
      height: 'auto',
      top: figureMarginTop
    });

    scroller.resize();
  }

  // scrollama event handlers
  function handleStepEnter(step, figure) {
    return function(response) {
      const iframe = document.querySelector("#scrolly iframe");

      step.each(function(i) {
        i === response.index ?
          y(this).addClass("is-active") :
          y(this).removeClass("is-active");
      });

      // update graphic based on step
      // figure.select("p").text(response.index + 1);
      // In this case it's a Flourish iframe slide
      iframe.src =
        iframe.src.replace(/#slide-.*/, "") + "#slide-" + response.index;
      console && console.log(iframe.src);
    };
  }

  function initScroller(scroller, step, figure, scope) {
    // 1. force a resize on load to ensure proper dimensions are sent to scrollama
    handleResize(scroller, step, figure);


    // Remove overflow:hidden from parent .col, it's preventing sticky behavior
    scope.parents(".col").css({
      overflow: "visible"
    });

    // 2. setup the scroller passing options
    // 		this will also initialize trigger observations
    // 3. bind scrollama event handlers (this can be chained like below)
    const handleStep = handleStepEnter(step, figure);
    scroller
      .setup({
        step: "#scrolly article .step",
        offset: 0,
        debug: false,
        threshold: 1
      })
      .onStepEnter(handleStep);
  }
});

//_____ search/Advanced.js _____//

define('search.Advanced')

.use('yellow.View')
.use('google.Tag')

.template('author',`
<a href="{{ url }}" title="{{title}}" class="search-meta__authors__author" y-name="author" >
	{{title}}
</a>
`)

.template('more',`
<span class="search-meta__authors__more clickable">
	More authors
</span>
`)



.template('article',`
<a y-name="article" class="search-exerpt" href="{{ url }}" title="{{title}}" target="_blank" rel="noopener noreferrer">
	<div class="search-exerpt__data">
		<div class="search-exerpt__date">
			{{date}}
		</div>
		<div class="search-exerpt__content">

			<h3 class="search-exerpt__headline">
				{{title}}
			</h3>
			<div class="search-exerpt__summary">
				{{ summary }}
			</div>
		</div>
	</div>
	{% if image %}
		<div class="search-exerpt__visual">
			<figure class="search-exerpt__media">
				<img class="search-exerpt__media__img" src="{{ image }}" alt="{{title}}" loading="lazy"/>
			</figure>
		</div>
	{% endif %}		
</a>
`)



.as(function (y, View, Tag, template) {
	var _scope;
	var _query;

	this.start = function (scope) {
		_scope = scope;

		var section = null;
		var from = scope.fetch("from").val();
		var to = scope.fetch("to").val();
		var order = null;

		if (from || to) {
			rangeLabel(from, to);
		}

		load(section, from, to, order);

		scope.fetch("submit").click(function (e) {
			load(section, from, to, order);
		});

		scope.fetch("query").keyup(function (e) {
			if (e.which === 13) {
				load(section, from, to, order);
			}
		});

		scope.fetch("select").click(function (e) {
			e.stopPropagation();
			if (!y(this).hasClass("search-select--active")) {
				close();
				y(this).addClass("search-select--active");
				y(this).fetch("options").show();
			}
		});

		var calenderFrom, calenderTo;

		calenderFrom = flatpickr(scope.fetch("from")[0], {
			altInput: true,
			altInputClass: "input-flatpickr",
			altFormat: "j F, Y",
			dateFormat: "Y-m-d",
			locale: "en",
			onClose: function (ranges, date) {
				from = date;
				calenderTo.open();
			},
		});

		calenderTo = flatpickr(scope.fetch("to")[0], {
			altInput: true,
			altInputClass: "input-flatpickr",
			altFormat: "j F, Y",
			dateFormat: "Y-m-d",
			locale: "en",
			onClose: function (ranges, date) {
				to = date;
				load(section, from, to, order);
				rangeLabel(from, to);
				close();
			},
		});

		scope.fetch("option").click(function (e) {
			e.stopPropagation();
			var select = y(this).fetch("select", "closest").data("name");
			var option = y(this).data("option");

			y(this).fetch("select", "closest").fetch("label").text(y(this).text());

			if (select === "section") {
				section = option;
				load(section, from, to, order);
				close();
			}

			if (select === "date") {
				if (option === "week") {
					from = ymd(date(-7));
					to = ymd(date());
					load(section, from, to, order);
					close();
				} else if (option === "month") {
					from = ymd(date(-31));
					to = ymd(date());
					load(section, from, to, order);
					close();
				} else if (option === "year") {
					from = ymd(date(-365));
					to = ymd(date());
					load(section, from, to, order);
					close();
				} else if (option === "range") {
					if (!from) {
						calenderFrom.setDate("1992-03-06");
					}
					if (!to) {
						calenderTo.setDate(new Date());
					}
					scope.fetch("range").show();
				} else {
					from = null;
					to = null;
					load(section, from, to, order);
					close();
				}
			}

			if (select === "order") {
				order = option;
				load(section, from, to, order);
				close();
			}
		});


		scope
		.fetch("articles")
		.fetch("more")
		.click(function () {
			scope.fetch("articles").fetch("article").show();
			scope.fetch("articles").fetch("more").hide();
		});

		scope.fetch("clear").click(function () {
			scope.fetch("articles").hide();
			scope.fetch("authors").hide();
			scope.fetch("clear").hide();
			scope.fetch("archive").show();
			scope.fetch("query").val("");
		});

		y("body").click(function () {
			close();
		});
	};

	function close() {
		_scope.fetch("select").removeClass("search-select--active");
		_scope.fetch("options").hide();
	}

	var rangeLabel = function (from, to) {
		if (from || to) {
			var label =
				(from ? mdy(date(from), "/") : "") +
				" - " +
				(to ? mdy(date(to), "/") : "");
			_scope.fetch("rangeLabel").text(label);
		}
	};

	var load = function (section, from, to, order) {
		var query = _scope.fetch('query').val();

		// load authors, only when there's a new query
		if (query && _query != query) {
			_query = query;
			_scope.fetch('authors').hide().fetch('mount').empty()
			_scope.fetch('authors-archive').hide().fetch('mount').empty();

			var qs = '?query=' + encodeURIComponent(query);
			
			// Tag event
			Tag.event('search', {
				search_term: query,
			});
			
			y.ajax(_scope.data('authors') + qs, {
				dataType: 'JSON',
			}).done(function (data) {
				var show = 4;
				if (data.length > 0) {
					
					var view = View.make(template('author'));
					var currentCount = 0;
					var archiveCount = 0;
					_scope.fetch('authors').show()
					for (var i = 0; i < data.length; i++) {
						var item = view.element(data[i]);
						
						if (data[i].type === 'current' ) {
							// Add current author
							currentCount++;
							_scope.fetch('authors-current').show().fetch('mount').append(item);
							if(currentCount > show) {
								item.hide();
							}
						} else {
							// Add archive author
							archiveCount++;
							_scope.fetch('authors-archive').show().fetch('mount').append(item);
							if(archiveCount > show) {
								item.hide();
							}
						}
					}
					// More button current
					if (currentCount > show) {
						_scope.fetch('authors-current').fetch('mount').append( 
							View.make(template('more')).element().click(function() {
								_scope.fetch('authors-current').fetch('author').show()
								y(this).hide()
							})
						)
					}
					// More button archive
					if (archiveCount > show) {
						_scope.fetch('authors-archive').fetch('mount').append( 
							View.make(template('more')).element().click(function() {
								_scope.fetch('authors-archive').fetch('author').show()
								y(this).hide()
							})
						)
					}
				}
			})
			.always(function () {
				_scope.fetch('clear').show();
			});
		}

		if (query) {
			_scope.fetch("articles").hide();
			_scope.fetch("articles").fetch("label").text("Found articles");
			_scope.fetch("articles").fetch("mount").empty();
			_scope.fetch("articles").fetch("more").hide();
			_scope.fetch("loading").show();

			_scope.fetch("archive").hide();

			var qs = "?query=" + encodeURIComponent(query);

			if (section) {
				qs += "&section=" + encodeURIComponent(section);
			}

			if (from) {
				qs += "&from=" + encodeURIComponent(from);
			}

			if (to) {
				qs += "&to=" + encodeURIComponent(to);
			}

			if (order) {
				qs += "&order=" + encodeURIComponent(order);
			}

			y.ajax(_scope.data("articles") + qs, {
				dataType: "JSON",
			})
				.done(function (data) {
					_scope.fetch("articles").show();
					var show = 10;
					if (data.length > 0) {
						var view = View.make(template("article"));
						for (var i = 0; i < data.length; i++) {
							var item = view.element(data[i]);
							if (i >= show) {
								item.hide();
							}
							_scope.fetch("articles").fetch("mount").append(item);
						}

						if (data.length > show) {
							_scope.fetch("articles").fetch("more").show();
						}
					} else {
						_scope.fetch("articles").fetch("label").text("No articles found");
					}
				})
				.always(function () {
					_scope.fetch("clear").show();
					_scope.fetch("loading").hide();
				});
		}
	};

	var date = function (input) {
		if (input < 0) {
			// days ago
			return new Date(new Date().getTime() + input * 24 * 3600 * 1000);
		} else if (y.isString(input)) {
			return new Date(input);
		} else {
			return new Date();
		}
	};

	var ymd = function (date, separator) {
		separator = separator || "-";
		return (
			date.getFullYear() +
			separator +
			String(date.getMonth() + 1).padStart(2, "0") +
			separator +
			String(date.getDate()).padStart(2, "0")
		);
	};

	var mdy = function (date, separator) {
		separator = separator || "-";
		return (
			String(date.getMonth() + 1).padStart(2, "0") +
			separator +
			String(date.getDate()).padStart(2, "0") +
			separator +
			date.getFullYear()
		);
	};
});

//_____ search/Archive.js _____//

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


//_____ newsletter/Banner.js _____//

define('newsletter.Banner')
.as(function(y){
	this.start = function(scope){
		var active = true;
		scope.fetch('submit').click(e => {
			if(! active) {
				return;
			}
			active = false;
			y.ajax(scope.data('url'), {
				type: 'POST',
				data: {
					email: scope.fetch('email').val(),
					name: scope.fetch('name').val(),
					tags: {
						[scope.data('newsletter')]: 1
					},
				},
				dataType: "json",
			})
			.done(function(data) {
				if (data.success) {
					scope.fetch('error').hide();
					scope.fetch('email').hide();
					scope.fetch('name').hide();
					scope.fetch('submit').hide();
					scope.fetch('done').show();
				} else {
					scope.fetch('error').text(data.message).show();
				}
			})
			.always(function() {
				active = true;
			});
		})
	}
})

//_____ google/Tag.js _____//

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

//_____ article/Regions.js _____//

// https://stackoverflow.com/questions/52576376/how-to-zoom-in-on-a-complex-svg-structure
define("article.Regions").as(function (y) {
	this.start = function (scope) {
		const oblast = scope.fetch("oblast");
		const map = scope.fetch("map");
		const paths = document.querySelectorAll("#map path");
		const scrollItem = scope.fetch("scroll");

		let h = scrollItem[0].offsetHeight;
		map[0].setAttribute("transform", `scale(0.9${h})`);

		paths.forEach(function (item, idx) {
			item.addEventListener("mouseover", function () {
				const id = item.getAttribute("title");
				oblast.html(`${idx}. ${id}`);
			});
			item.addEventListener(
				"click",
				function (e) {
					const id = item.getAttribute("id");
					window.open("/tag/" + id);
				},
				false
			);
		});
		addEventListener("resize", function (e) {
			let h = scrollItem[0].offsetHeight;
			map[0].setAttribute("transform", `scale(0.9${h})`);
		});
	};
});


//_____ contribute/Currency.js _____//

define("contribute.Currency")	
.as(function (y) {
	this.start = function (scope) {
		const zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		if(zone.indexOf('Europe/') === 0) {
			scope.text('€')
		} else {
			scope.text('$')
		}
	}
});



		