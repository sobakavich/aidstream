var CsvImportStatusManager = {
    getValidData: function () {
        $.ajax({
            url: 'get-valid-data',
            type: 'GET'
        }).success(function (response) {
            var parentDiv = $('div.valid-data');

            if (typeof response == 'string') {
                parentDiv.append(response);
            } else {
                for (var i=0; i<=response.length; i++) {
                    parentDiv.append(JSON.stringify(response[i]));
                }
            }
        });
    },
    getInvalidData: function () {
        $.ajax({
            url: 'get-invalid-data',
            method: 'GET'
        }).success(function (response) {
            var parentDiv = $('div.invalid-data');

            if (typeof response == 'string') {
                parentDiv.append(response);
            } else {
                for (var i=0; i<response.length; i++) {
                    var childDiv = $('<div><input type="checkbox" disabled="disabled" value="' + i + '">' + JSON.stringify(response[i]) + '</div>');

                    parentDiv.append(childDiv);
                }
            }
        });
    }
};

$(document).ready(function () {
    setTimeout(function () {
        CsvImportStatusManager.getValidData();
        CsvImportStatusManager.getInvalidData();
    }, 1000);
});
