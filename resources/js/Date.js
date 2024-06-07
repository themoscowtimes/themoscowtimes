define("Date")
  .use("yellow.View")
  .as(function(y, View) {
    var _scope;
    var _value;
    var _time;

    var _year;
    var _month;
    var _day;
    var _hour;
    var _minute;

    var _view;

    this.start = function(scope) {
      _scope = scope;
      _value = scope.data("value");
      _time = scope.data("time");

      moment.locale("en");

      var mom = moment(_value);
      _year = mom.year();
      _month = mom.month();
      _day = mom.date();
      _hour = mom.hour();
      _minute = mom.minute();
      _view = View.make(_scope.template("calendar"));

      render(_year, _month);
    };

    var data = function(year, month) {
      var mom = moment(year + "-01-01 00:00").add(month, "M");

      var start = mom.format("d") - 1;
      var days = mom.endOf("month").format("D");
      var weeks = [];
      var week = [];

      for (var i = 0; i < start; i++) {
        week.push(false);
      }
      for (var day = 1; day <= days; day++) {
        week.push(day);
        if (week.length == 7) {
          weeks.push(week);
          week = [];
        }
      }
      if (week.length > 0) {
        for (var i = week.length; i < 7; i++) {
          week.push(false);
        }
        weeks.push(week);
      }

      var days = moment.weekdaysShort();
      days.push(days.shift());
      return {
        current: {
          year: _year,
          month: _month,
          day: _day,
          hour: _hour,
          minute: _minute
        },
        year: year,
        month: mom.format("MMMM"),
        monthnumber: month,
        days: days,
        weeks: weeks,
        active: year == _year && month == _month ? _day : false,
        time: _time
      };
    };

    var render = function(year, month) {
      var calendar = _view.element(data(year, month), {
        previous: function() {
          month--;
          if (month == -1) {
            year--;
            month = 11;
          }
          render(year, month);
        },
        next: function() {
          month++;
          if (month == 12) {
            year++;
            month = 0;
          }
          render(year, month);
        },
        date: function(year, month, day) {
          _year = year;
          _month = month;
          _day = day;
          _scope.fetch("day").removeClass("active");
          _scope.fetch("day").addClass("inactive");
          _scope.fetch("day-" + day).removeClass("inactive");
          _scope.fetch("day-" + day).addClass("active");
          update();
        },
        hourup: function() {
          _hour++;
          if (_hour == 24) {
            _hour = 0;
          }
          update();
        },
        hourdown: function() {
          _hour--;
          if (_hour == -1) {
            _hour = 23;
          }
          update();
        },
        hourchange: function() {
          _hour = Number(
            _scope
              .fetch("hour")
              .val()
              .replace(/[^0-9]+/g, "")
          );
          update();
        },
        minuteup: function() {
          _minute++;
          if (_minute == 60) {
            _minute = 0;
          }
          update();
        },
        minutedown: function() {
          _minute--;
          if (_minute == -1) {
            _minute = 59;
          }
          update();
        },
        minutechange: function() {
          _minute = Number(
            _scope
              .fetch("minute")
              .val()
              .replace(/[^0-9]+/g, "")
          );
          update();
        }
      });
      _scope
        .fetch("container")
        .empty()
        .append(calendar);
    };

    this.value = function() {
      return _value;
    };

    var update = function() {
      var mom = moment(_year + "-01-01 00:00")
        .add(_month, "M")
        .add(_day - 1, "d")
        .add(_hour, "h")
        .add(_minute, "m");
      _value = mom.format("YYYY-MM-DD HH:mm");

      _scope.change();
    };
  });
