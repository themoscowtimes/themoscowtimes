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