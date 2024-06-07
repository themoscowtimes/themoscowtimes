define("Embed").as(function(y) {
  this.start = function(scope) {
    // Create video wrapper
    var selectors = [
      "iframe[src*='player.vimeo.com']",
      "iframe[src*='www.youtube.com']",
      "iframe[src*='www.kickstarter.com']",
      "iframe[src*='vk.com']",
      "object",
      "embed"
    ];

    for (var i = 0; i < selectors.length; i++) {
      scope.find(selectors[i]).each(function() {
        var height =
            this.tagName.toLowerCase() == "object"
              ? y(this).attr("height")
              : y(this).height(),
          aspectRatio = height / y(this).width();
        y(this)
          .wrap('<div class="fluid-width-video-wrapper"></div>')
          .parent(".fluid-width-video-wrapper")
          .css("padding-top", aspectRatio * 100 + "%");
        y(this)
          .removeAttr("height")
          .removeAttr("width");
      });
    }

    // create twitter embed
    var html = scope.html().trim();
    if (
      html.indexOf("https://twitter.com") === 0 ||
      html.indexOf("https://www.twitter.com") === 0
    ) {
      y.ajax(
        "https://publish.twitter.com/oembed?url=" + encodeURIComponent(html),
        {
          dataType: "JSONP"
        }
      ).done(function(data) {
        scope.html(data.html);
      });
    }
  };
});
