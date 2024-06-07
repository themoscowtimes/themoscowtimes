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
