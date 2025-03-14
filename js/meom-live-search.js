(function($) {
  $(function() {

    function livesearch() {
      var input = $( this );
      var form = input.parents( 'form' );
      var formData = form.serialize();
      var loader = $( '.meom-live-search__loader' );

      $.get( '/wp-json/meom-live-search/v1/search?' + formData + '&lang=' + window.liveSearch.lang, function(response) {
        loader.removeClass( 'meom-live-search__loader--show' );
        $( window.liveSearch.resultsElement ).html( response.resultHTML );
      });

      loader.addClass( 'meom-live-search__loader--show' );
    }

    $(window.liveSearch.searchInput).on( 'keyup', throttle( function(event) {
      livesearch.bind( event.target )();
    }, 400, { leading: false }));

    $(window.liveSearch.searchInput).on( 'focusin', function(event) {
      $( window.liveSearch.resultsElement ).addClass( 'active' );
    });
    $(window.liveSearch.searchInput).on( 'focusout', function(event) {
      $( window.liveSearch.resultsElement ).removeClass( 'active' );
    });

  });
})(jQuery);

// Returns a function, that, when invoked, will only be triggered at most once
// during a given window of time. Normally, the throttled function will run
// as much as it can, without ever going more than once per `wait` duration;
// but if you'd like to disable the execution on the leading edge, pass
// `{leading: false}`. To disable execution on the trailing edge, ditto.
// https://stackoverflow.com/a/27078401/10883972
function throttle(func, wait, options) {
  var context, args, result;
  var timeout = null;
  var previous = 0;
  if (!options) options = {};
  var later = function() {
    previous = options.leading === false ? 0 : Date.now();
    timeout = null;
    result = func.apply(context, args);
    if (!timeout) context = args = null;
  };
  return function() {
    var now = Date.now();
    if (!previous && options.leading === false) previous = now;
    var remaining = wait - (now - previous);
    context = this;
    args = arguments;
    if (remaining <= 0 || remaining > wait) {
      if (timeout) {
        clearTimeout(timeout);
        timeout = null;
      }
      previous = now;
      result = func.apply(context, args);
      if (!timeout) context = args = null;
    } else if (!timeout && options.trailing !== false) {
      timeout = setTimeout(later, remaining);
    }
    return result;
  };
};
