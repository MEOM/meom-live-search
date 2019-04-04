(function($) {
  $(function() {

    function livesearch() {
      var input = $(this);
      var form = input.parents('form');
      var formData = form.serialize();
      var loader = $('.meom-live-search__loader');

      $.get('/wp-json/meom-live-search/v1/search?' + formData + '&lang=' + window.liveSearch.lang, function(response) {
        loader.removeClass('meom-live-search__loader--show');
        $(window.liveSearch.resultsElement).html(response.resultHTML);
      });

      loader.addClass('meom-live-search__loader--show');
    }

    $(window.liveSearch.searchInput).on('keyup', _.throttle(function(event) {
      livesearch.bind(event.target)();
    }, 400, { leading: false }));

  });
})(jQuery);
