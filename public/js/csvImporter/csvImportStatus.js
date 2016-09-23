var submitButton = $('input#submit-valid-activities');
var count = 0;
var counter = $('input#value');
var transferComplete = false;
var clearInvalidButton = $('#clear-invalid');
var validParentDiv = $('.valid-data');
var invalidParentDiv = $('.invalid-data');

var CsvImportStatusManager = {
    getParentDiv: function (selector) {
        return $('div.' + selector);
    },
    enableImport: function (response) {
        if (response.render) {
            if (response.render.length > 18) {
                submitButton.fadeIn().removeClass('hidden');
            } else if (response.render == 'No data available.') {
                transferComplete = true;
            }
        }
    },
    callAsync: function (url, methodType) {
        return $.ajax({
            url: url,
            type: methodType
        });
    },
    getValidData: function () {
        CsvImportStatusManager.callAsync('get-valid-data', 'GET').success(function (validData) {
            var parentDiv = CsvImportStatusManager.getParentDiv('valid-data');

            if (validParentDiv.html() != 'No data available.') {
                parentDiv.append(validData.render);
            }

            CsvImportStatusManager.enableImport(validData);
        }).error(function (error) {
            // TODO: handle error

            var parentDiv = CsvImportStatusManager.getParentDiv('valid-data');

            parentDiv.append('Looks like something went wrong.');
        });
    },
    getInvalidData: function () {
        CsvImportStatusManager.callAsync('get-invalid-data', 'GET').success(function (invalidData) {
            var parentDiv = CsvImportStatusManager.getParentDiv('invalid-data');

            if (invalidParentDiv.html() != 'No data available.') {
                parentDiv.append(invalidData.render);
            }
        }).error(function (error) {
            // TODO: handle error
            var parentDiv = CsvImportStatusManager.getParentDiv('invalid-data');

            parentDiv.append('Looks like something went wrong.');
        });
    },
    ifParentIsEmpty: function (className) {
        var parentDiv = CsvImportStatusManager.getParentDiv(className);

        return parentDiv.is(':empty');
    },
    getCurrentData: function (className) {
        return $('div.' + className);
    },
    isTransferComplete: function () {
        CsvImportStatusManager.callAsync('/import-activity/check-status', 'GET').success(function (response) {
            var r = JSON.parse(response);

            if (r.status == 'Complete') {
                transferComplete = true;
            }
        }).error(function (error) {
            // TODO: handle error
        });
    },
    getRemainingInvalidData: function () {
        CsvImportStatusManager.callAsync('/import-activity/remaining-invalid-data', 'GET').success(function (response) {
            var parentDiv = CsvImportStatusManager.getParentDiv('invalid-data');

            if (invalidParentDiv.html() != 'No data available.') {
                parentDiv.append(response.render);
            }
        });
    },
    getRemainingValidData: function () {
        CsvImportStatusManager.callAsync('/import-activity/remaining-valid-data', 'GET').success(function (response) {
            var parentDiv = CsvImportStatusManager.getParentDiv('valid-data');

            if (validParentDiv.html() != 'No data available.') {
                parentDiv.append(response.render);
            }

        });
    },
    showClearButton: function () {
        clearInvalidButton.show();
    }
};

$(document).ready(function () {
    clearInvalidButton.hide();

    var interval = setInterval(function () {
        CsvImportStatusManager.isTransferComplete();
        if (CsvImportStatusManager.ifParentIsEmpty('invalid-data')) {
            CsvImportStatusManager.getValidData();
            CsvImportStatusManager.getInvalidData();
        } else {
            CsvImportStatusManager.getRemainingValidData();
            CsvImportStatusManager.getRemainingInvalidData();
        }

        if (transferComplete) {
            test();
            CsvImportStatusManager.showClearButton();
            clearInterval(interval);
        }
    }, 8000);

    clearInvalidButton.on('click', function () {
        CsvImportStatusManager.callAsync('/import-activity/clear-invalid-activities', 'GET').success(function (response) {
            if (!CsvImportStatusManager.ifParentIsEmpty('invalid-data')) {
                if (response == 'cleared') {
                    CsvImportStatusManager.getCurrentData('invalid-data').empty();
                }
            }
        });
    });
});
