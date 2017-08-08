define(['jquery', 'TYPO3/CMS/FbitTslive/Module'], function ($) {
    'use strict';

    var Tslive = {
        ajaxUrl: TYPO3.settings.ajaxUrls['LiveEditorController::parseTS'],
        runningRequest: null,

        /**
         * @return void
         */
        initialize: function() {
            $('#typoscript').on('keyup', function(event) {
                if (Tslive.runningRequest) {
                    Tslive.runningRequest.abort();
                }

                Tslive.runningRequest = $.ajax(Tslive.ajaxUrl, {
                    method: 'POST',
                    data: {ts: $(this).val()},
                    success: function (data) {
                        $('#tsoutput').text(data.parsedTs);
                    }
                })
            });
        }
    };

    Tslive.initialize();
});