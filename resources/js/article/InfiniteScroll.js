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