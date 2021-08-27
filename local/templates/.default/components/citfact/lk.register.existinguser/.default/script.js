document.addEventListener('App.Ready', function (e) {
  var searchInput = $('[data-search-input-user]');

  searchInput.on('keyup', function(event) {
    $('[data-search-user-id]').val(''); // set local/client/app/js/suggestions.js

    let thisSearchInput = $(this);
    if (window.timerSearchuser) {
      clearTimeout(window.timerSearchuser);
    }
    window.timerSearchuser = setTimeout(function () {
      var params = {
        'user': thisSearchInput.val(),
        'contragent': $('[data-search-contragent]').val(),
      };

      $.ajax({
        type: "POST",
        url: thisSearchInput.data('search-input-user'),
        data: params,
        success: function (data) {
          let jsonData;
          if (data) {
            try {
              jsonData = JSON.parse(data)
            } catch ( e ) {}
          }

          if (!jsonData || !jsonData.SUCCESS) {
            return;
          }

          Am.suggestions.initUsers(thisSearchInput, jsonData.MESSAGE);
        },
        error: function (er) {
          console.log('error', er);
        }
      });
    }, 500);
  });
});