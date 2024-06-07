define("IntObserver").as({
  init: (targetElem, options, fn, ...args) => {
    const callback = (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          return fn(observer, ...args);
        }
      });
    };
    const observer = new IntersectionObserver(callback, options);
    observer.observe(targetElem);
  }
});
