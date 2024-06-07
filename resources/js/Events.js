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
