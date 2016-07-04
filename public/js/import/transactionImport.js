var TransactionImporter = {
    confirmTransactions: function () {
        $.ajax({
            url: route,
            method: 'get',
            data: {filename: filename}
        }).success(function (response) {
            TransactionImporter.displayValidatedTransactions(response);
        });
    },
    displayValidatedTransactions: function (data) {
        var submit = $('input#saveValidatedTransactions');
        var validTransactionHolder = $('div#transaction-status-holder');

        (validTransactionHolder.append(data)).fadeIn(10, function () {
            submit.fadeIn();
        });
    },
    bringInTransactionsForConfirmation: function () {
        setTimeout(TransactionImporter.confirmTransactions(), 50000);
    }
};

$(document).ready(function () {
    TransactionImporter.bringInTransactionsForConfirmation();
});
