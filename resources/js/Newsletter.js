define('Newsletter')
  .as(function(y) {
    var _scope;

    var validateEmail = function(email) {
      var regex = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
      return email.match(regex);
    };

    this.start = function(scope) {
      _scope = scope;
      var active = true;

      scope.fetch("email").on("keyup", function() {
        validateEmail(_scope.fetch("email").val()) ?
          scope.fetch('error').hide() :
          scope.fetch('error').text("Incorrect format").show();
      });

      scope.fetch('submit').click(function() {
        if (active) {
          var email = _scope.fetch('email').val();
          if (validateEmail(email)) {
            scope.fetch('error').hide();
            var url = _scope.data('url');
            active = false;
            y.ajax(url, {
                type: 'POST',
                data: {
                  email: email,
                  name: _scope.fetch('name').val(),
                  //country: _scope.fetch('country').val()
                },
                dataType: 'json'
              })
              .done(function(data) {
                if (data.success) {
                  scope.fetch('error').hide();
                  scope.fetch('email').hide();
                  scope.fetch('name').hide();
                  scope.fetch('submit').hide();
                  scope.fetch('done').show();
                } else {
                  scope
                    .fetch('error')
                    .text(data.message)
                    .show();
                }
              })
              .always(function() {
                active = true;
              });
          } else {
            scope.fetch('error').text("Email is required").show();
          }
        }
      });
    };
  });