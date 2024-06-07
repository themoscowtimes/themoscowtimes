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
