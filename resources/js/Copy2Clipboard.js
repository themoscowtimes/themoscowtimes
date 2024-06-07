define("Copy2Clipboard").as(function(y) {
  this.start = function(scope) {
    const url = document.location.href;
    scope.fetch("copy").on("click", function(e) {
      e.preventDefault();
      cp2clip(scope, url, true);
    });
  };

  function cp2clip(scope, val, showMessage) {
    const tempInput = y("<input>");
    y("body").append(tempInput);
    tempInput.val(val).select();
    document.execCommand("copy");
    tempInput.remove();
    if (typeof showMessage === "undefined") {
      showMessage = true;
    }
    notice(scope.fetch("to_copy"), scope.fetch("copied"));
  }

  function notice(from, to) {
    from.fadeOut("slow", () => {
      to.fadeIn("slow", () => {
        setTimeout(() => {
          to.hide();
          from.fadeIn("slow");
        }, 1000);
      });
    });
  }
});
