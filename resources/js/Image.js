define("Image").as(function(y) {
  this.start = function(scope) {
    var src = scope.data("src");
    if (src) {
      if (!y.isArray(src)) {
        src = [src];
      }
      load(scope[0], src);
    }
  };

  var load = function(image, images) {
    if (images.length > 0) {
      var src = images.shift();
      image.onerror = function() {
        image.onerror = null;
        image.onload = null;
        load(image, images);
      };
      image.onload = function(e) {
        image.onerror = null;
        image.onload = null;
        if (image.naturalHeight <= 90) {
          load(image, images);
        }
      };
      y(image).attr("src", src);
    }
  };
});
