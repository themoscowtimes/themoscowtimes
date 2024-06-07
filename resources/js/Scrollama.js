// For graph interactions
// https://russellgoldenberg.github.io/scrollama/sticky-overlay/

define("Scrollama").as(function(y) {
  this.start = function(scope) {
    const figure = scope.fetch("figure");
    const article = scope.fetch("article");
    const step = scope.fetch("step");
    const scroller = scrollama();
    initScroller(scroller, step, figure, scope);
  };

  // generic window resize listener event
  function handleResize(scroller, step, figure) {
    let stepH = Math.floor(window.innerHeight * .75);
    step.css({ height: stepH });

    let figureHeight = window.innerHeight / 2;
    let figureMarginTop = (window.innerHeight - figureHeight) / 5;

    figure.css({
      height: 'auto',
      top: figureMarginTop
    });

    scroller.resize();
  }

  // scrollama event handlers
  function handleStepEnter(step, figure) {
    return function(response) {
      const iframe = document.querySelector("#scrolly iframe");

      step.each(function(i) {
        i === response.index ?
          y(this).addClass("is-active") :
          y(this).removeClass("is-active");
      });

      // update graphic based on step
      // figure.select("p").text(response.index + 1);
      // In this case it's a Flourish iframe slide
      iframe.src =
        iframe.src.replace(/#slide-.*/, "") + "#slide-" + response.index;
      console && console.log(iframe.src);
    };
  }

  function initScroller(scroller, step, figure, scope) {
    // 1. force a resize on load to ensure proper dimensions are sent to scrollama
    handleResize(scroller, step, figure);


    // Remove overflow:hidden from parent .col, it's preventing sticky behavior
    scope.parents(".col").css({
      overflow: "visible"
    });

    // 2. setup the scroller passing options
    // 		this will also initialize trigger observations
    // 3. bind scrollama event handlers (this can be chained like below)
    const handleStep = handleStepEnter(step, figure);
    scroller
      .setup({
        step: "#scrolly article .step",
        offset: 0,
        debug: false,
        threshold: 1
      })
      .onStepEnter(handleStep);
  }
});