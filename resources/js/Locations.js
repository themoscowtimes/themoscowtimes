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
