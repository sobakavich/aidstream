var submitButton = $('input#submit-valid-activities');
var cancelButton = $('input#cancel-import');
var checkAll = $('div#checkAll');
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
                submitButton.fadeIn("slow").removeClass('hidden');
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
    ifParentIsEmpty: function (className) {
        var parentDiv = CsvImportStatusManager.getParentDiv(className);

        return parentDiv.is(':empty');
    },
    isTransferComplete: function () {
        CsvImportStatusManager.callAsync('/import-activity/check-status', 'GET').success(function (response) {
            var r = JSON.parse(response);

            if (r.status == 'Error') {
                cancelButton.fadeIn('slow').removeClass('hidden');

                transferComplete = null;
            }

            if (r.status == 'Complete') {
                transferComplete = true;
                cancelButton.fadeIn('slow').removeClass('hidden');
                checkAll.fadeIn('slow').removeClass('hidden');
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
    getData: function () {
        CsvImportStatusManager.callAsync('get-data', 'GET').success(function (response) {
            var validParentDiv = CsvImportStatusManager.getParentDiv('valid-data');
            var invalidParentDiv = CsvImportStatusManager.getParentDiv('invalid-data');

            if (response.validData) {
                if (validParentDiv.html() != 'No data available.') {
                    validParentDiv.append(response.validData.render);
                }

                CsvImportStatusManager.enableImport(response.validData);
            }

            if (response.invalidData) {
                if (invalidParentDiv.html() != 'No data available.') {
                    invalidParentDiv.append(response.invalidData.render);
                }
            }
        }).error(function (error) {
            var validParentDiv = CsvImportStatusManager.getParentDiv('valid-data');
            var invalidParentDiv = CsvImportStatusManager.getParentDiv('invalid-data');

            validParentDiv.append('Looks like something went wrong.');
            invalidParentDiv.append('Looks like something went wrong.');
        });
    }
};

$(document).ready(function () {
    accordionInit();
    clearInvalidButton.hide();

    var interval = setInterval(function () {
        CsvImportStatusManager.isTransferComplete();

        if (CsvImportStatusManager.ifParentIsEmpty('invalid-data') && CsvImportStatusManager.ifParentIsEmpty('valid-data')) {
            CsvImportStatusManager.getData();
        } else {
            CsvImportStatusManager.getRemainingValidData();
            CsvImportStatusManager.getRemainingInvalidData();
        }

        if (null == transferComplete) {
            window.location = '../import-activity/upload-csv-redirect';
        }

        if (transferComplete) {
            accordionInit();
            clearInterval(interval);
        }
    }, 5000);
});
