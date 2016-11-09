$(document).ready(function () {
    var status;

    var timer = setInterval(function () {
        $.ajax({
            url: checkSessionRoute,
            method: 'GET'
        }).success(function (response) {
            var placeHolder = $('div#import-status-placeholder');

            if (response.status == 'Complete') {
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + "Csv File Processing " + response.status + "</a>");
                status = 'Complete';
            } else if (response.status == 'Processing') {
                placeHolder.empty().append("<a href='/import-activity/import-status'>" + "Csv File " + response.status + "</a>");
            }
        });

        if (status == 'Complete') {
            clearInterval(timer);
        }
    }, 3000);
});
