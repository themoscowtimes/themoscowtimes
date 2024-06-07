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