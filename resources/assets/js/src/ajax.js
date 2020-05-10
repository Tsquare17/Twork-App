;(function($){
    'use strict';

    $.ajax({
        type: 'POST',
        url: tworkRequest.ajaxurl,
        data: {
            action: 'exampleAjaxMethod',
            nonce: tworkRequest.ajaxnonce,
        },
        success: function (response) {
            console.log(response);
        },
        error: function (e) {
            console.log(e);
        }
    })
})(jQuery, window, document);
