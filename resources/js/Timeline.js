define("Timeline").as(function(y) {
  this.start = function(scope) {
    y(window).on("scroll", function() {
      timeLine(scope);
    });
  };

  function timeLine(elem) {
    let start = window.scrollY;
    let end = document.body.offsetHeight - window.innerHeight;
    let position = Math.floor((start / end) * 100);
    return elem.css({ width: position + "%" });
  }
});
