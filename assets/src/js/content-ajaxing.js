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
          Drupal.ajax({ url: elem.dataset.ajaxCommands, httpMethod: 'GET' }).execute();
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

      // Ajax Commands.
      const ajaxElsTrigger = context.querySelectorAll("[data-ajax-trigger]:not(.observing)");
      ajaxElsTrigger.forEach(el => {
        el.classList.add("observing");
        el.addEventListener('trigger-ajax', e => {
          Drupal.ajax({ url: el.dataset.ajaxTrigger, httpMethod: 'GET' }).execute();
        });
      });

      // Load Ajaxable content.
      const ajaxElsNow = document.querySelectorAll("[data-ajax-now]");

      // Data obj here so we don't make multiple calls if we don't need to.
      let data = {};
      ajaxElsNow.forEach(element => {
        if (!data[element.dataset.ajaxNow]) {
          data[element.dataset.ajaxNow] = [];
        }
        data[element.dataset.ajaxNow].push(element);
      });
      for (const [url, elements] of Object.entries(data)) {
        fetch(url)
          .then((response) => response.text())
          .then((text) => {
            elements.forEach(element => {
              element.innerHTML = text;
              element.removeAttribute("data-ajax-now");
              element.classList.add("ajax-now--loaded");
            });
          });
      };
    },
  };

  // eslint-disable-next-line no-undef
})(jQuery, Drupal, drupalSettings);
