var submitButton = $('input#submit-valid-activities');
var validIndices = [];
var invalidIndices = [];
var count = 0;
var counter = $('input#value');
var dataCount = 0;
var transferComplete = false;

var CsvImportStatusManager = {
    getParentDiv: function (selector) {
        return $('div.' + selector);
    },
    enableImport: function (response) {
        if (response.render) {
            if (response.render.length > 18) {
                submitButton.fadeIn().removeClass('hidden');
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
        CsvImportStatusManager.callAsync('get-invalid-data', 'GET').success(function (validData) {
            if (validData.transferComplete == true) {
                transferComplete = true;
            }
            var parentDiv = CsvImportStatusManager.getParentDiv('valid-data');
            // validIndices = JSON.parse(invalidData.indices);
            // console.log(validData);
            // readIndices.push(validData.indices);

            parentDiv.append(validData.render);

            CsvImportStatusManager.enableImport(validData);

            count++;

            counter.val(count);

            return validData;
        });
        // if (indices) {
        //     CsvImportStatusManager.callAsync('get-valid-data', 'GET', indices).success(function (validData) {
        //         var parentDiv = CsvImportStatusManager.getParentDiv('valid-data');
        //         validIndices = JSON.parse(invalidData.indices);
        //         // console.log(validData);
        //         // readIndices.push(validData.indices);
        //
        //         parentDiv.append(validData.render);
        //
        //         CsvImportStatusManager.enableImport(validData);
        //     });
        // } else {
        // }
    },
    getInvalidData: function () {
        CsvImportStatusManager.callAsync('get-invalid-data', 'GET').success(function (invalidData) {
            var parentDiv = CsvImportStatusManager.getParentDiv('invalid-data');
            // invalidIndices = JSON.parse(invalidData.indices);

            parentDiv.append(invalidData.render);

            return invalidData;
        });
    },
    ifParentIsEmpty: function (className) {
        var parentDiv = CsvImportStatusManager.getParentDiv(className);

        return parentDiv.is(':empty');

        // var invalidParentDiv = CsvImportStatusManager.getParentDiv(className);

    },
    getCurrentData: function (className) {
        return $('div.' + className);
    }
};

$(document).ready(function () {
    var interval = setInterval(function () {
        if (CsvImportStatusManager.ifParentIsEmpty('valid-data')) {
            CsvImportStatusManager.getValidData();
        } else {
            clearInterval(interval);
            var validData = CsvImportStatusManager.getValidData();

            if (transferComplete) {
                clearInterval(interval);
            }

            // CsvImportStatusManager.getValidData(validIndices);
            // console.log(CsvImportStatusManager.getCurrentData('valid-data').html());
        }

        if (CsvImportStatusManager.ifParentIsEmpty('invalid-data')) {
            CsvImportStatusManager.getInvalidData();
        } else {
            clearInterval(interval);
            var invalidData = CsvImportStatusManager.getInvalidData();

            if (transferComplete) {
                clearInterval(interval);
            }
            // console.log(CsvImportStatusManager.getCurrentData('invalid-data').html());
        }
    }, 2000);
});
