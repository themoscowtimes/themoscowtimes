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
