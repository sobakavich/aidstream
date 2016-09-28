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
    showClearButton: function () {
        clearInvalidButton.show();
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
            } else if (response.invalidData) {
                if (invalidParentDiv.html() != 'No data available.') {
                    invalidParentDiv.append(response.invalidData.render);
                }
            } else {
                if (validParentDiv.html() != 'No data available.') {
                    validParentDiv.append(response.validData.render);
                }

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
    test();
    clearInvalidButton.hide();

    var interval = setInterval(function () {
        CsvImportStatusManager.isTransferComplete();

        if (CsvImportStatusManager.ifParentIsEmpty('invalid-data') && CsvImportStatusManager.ifParentIsEmpty('valid-data')) {
            CsvImportStatusManager.getData();
        } else {
            CsvImportStatusManager.getRemainingValidData();
            CsvImportStatusManager.getRemainingInvalidData();
        }

        if (transferComplete) {
            test();
            CsvImportStatusManager.showClearButton();
            clearInterval(interval);
        }
    }, 5000);

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
