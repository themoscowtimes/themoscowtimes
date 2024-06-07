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