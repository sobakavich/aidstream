var submitButton = $('input#submit-valid-activities');
var count = 0;
var counter = $('input#value');
var transferComplete = false;

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

            parentDiv.append(validData.render);

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

            parentDiv.append(invalidData.render);
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

            parentDiv.append(response.render);
        });
    },
    getRemainingValidData: function () {
        CsvImportStatusManager.callAsync('/import-activity/remaining-valid-data', 'GET').success(function (response) {
            var parentDiv = CsvImportStatusManager.getParentDiv('valid-data');

            parentDiv.append(response.render);
        });
    }
};

$(document).ready(function () {
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
            clearInterval(interval);
        }
    }, 4000);
});
