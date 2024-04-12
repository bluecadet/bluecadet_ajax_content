/* eslint-disable no-unused-vars */
(($, Drupal, drupalSettings) => {

  let contentObserverOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 1.0
  }

  const ajaxContentObserverCallback = (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        let elem = entry.target;

        // Load content.
        if (elem.dataset.ajaxScroll) {
          fetch(elem.dataset.ajaxScroll)
            .then((response) => {
              return response.text();
            })
            .then((text) => {
              elem.innerHTML = text;
              elem.classList.add("loaded");

              Drupal.attachBehaviors(elem);

              if (elem.dataset.ajaxEvent) {
                const timelineDataEvent = new Event(elem.dataset.ajaxEvent);
                window.dispatchEvent(timelineDataEvent);
              }
            });
        }

        if (elem.dataset.ajaxCommands) {
          // fetch(elem.dataset.ajaxCommands)
          //   .then((response) => {
          //     // console.log(response, response.text(), response.status, response.statusText);
          //     return response.text();
          //   })
          //   .then((text) => {

          //     console.log(text);

              // elem.innerHTML = text;
              // elem.classList.add("loaded");

              // Drupal.attachBehaviors(elem);

              // if (elem.dataset.ajaxEvent) {
              //   const timelineDataEvent = new Event(elem.dataset.ajaxEvent);
              //   window.dispatchEvent(timelineDataEvent);
              // }
          // });

          Drupal.ajax({url: elem.dataset.ajaxCommands, httpMethod: 'GET'}).execute();
        }

        contentObserver.unobserve(elem);
      }

    });
  }
  let contentObserver = new IntersectionObserver(ajaxContentObserverCallback, contentObserverOptions);

  Drupal.behaviors.customAjaxContent = {
    attach: function (context, settings) {
      // Simple Loads.
      const ajaxEls = context.querySelectorAll("[data-ajax-scroll]:not(.observing)");
      ajaxEls.forEach(el => {
        contentObserver.observe(el);
        el.classList.add("observing");
      });

      // Ajax Commands.
      const ajaxElsComm = context.querySelectorAll("[data-ajax-commands]:not(.observing)");
      ajaxElsComm.forEach(el => {
        contentObserver.observe(el);
        el.classList.add("observing");
      });

      // Load Ajaxable content.
      const ajaxElsNow = document.querySelectorAll("[data-ajax-now]");

      ajaxElsNow.forEach(element => {
        fetch(element.dataset.ajaxNow)
          .then((response) => response.text())
          .then((text) => {
            element.innerHTML = text;
            element.removeAttribute("data-ajax-now");
            element.classList.add("ajax-now--loaded");
          });
      });
    },
  };

  // eslint-disable-next-line no-undef
})(jQuery, Drupal, drupalSettings);
