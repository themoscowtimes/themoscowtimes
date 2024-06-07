define("Youtube").as(function(y) {
  this.start = function(scope) {
    var video = scope.data("video");
    scope.fetch("poster").click(function() {
      y(this).hide();
      scope
        .fetch("player")
        .html(
          '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' +
            video +
            '?autoplay=1&loop=1&rel=0&wmode=transparent&mute=1" frameborder="0" allowfullscreen wmode="Opaque"></iframe>'
        )
        .show();
    });
  };
});

// Toggle for homepage videos carousel
define("Videogallery").as(function(y) {
  this.start = function(scope) {
    const toggle = scope.fetch("player-toggle");
    const player = scope.fetch("video-player");

    y(toggle)
      .first()
      .addClass("active");

    toggle.click(function(e) {
      y(this)
        .addClass("active")
        .siblings()
        .removeClass("active");
      player.html(
        '<iframe id="video-' +
          y(this).data("id") +
          '" src="https://www.youtube.com/embed/' +
          y(this).data("id") +
          '?&autoplay=0&loop=0&controls=1&rel=0&wmode=transparent" allowfullscreen="" wmode="Opaque" width="100%" height="100%" frameborder="0"></iframe>'
      );
    });
  };
});
