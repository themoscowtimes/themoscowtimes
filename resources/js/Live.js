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
