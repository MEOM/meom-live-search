(function($) {
  $(function() {

    function livesearch() {
      var input = $(this);
      var form = input.parents('form');
      var formData = form.serialize();
      var spinner = $('.meom-live-search__spinner');

      $.get('/wp-json/meom-live-search/v1/search?' + formData + '&lang=' + window.liveSearch.lang, function(response) {
        spinner.removeClass('meom-live-search__spinner--show');
        $(window.liveSearch.resultsElement).html(response.resultHTML);
      });

      spinner.addClass('meom-live-search__spinner--show');
    }

    $(window.liveSearch.searchInput).on('keyup', _.throttle(function(event) {
      livesearch.bind(event.target)();
    }, 400, { leading: false }));

  });
})(jQuery);
