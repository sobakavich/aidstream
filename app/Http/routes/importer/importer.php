<?php

$router->group(['namespace' => 'CsvImporter'], function ($router) {
    $router->post('/activity/{activity}/import-transaction-csv', [
        'as'   => 'import.transaction',
        'uses' => 'ImportController@transaction'
    ]);

    $router->get('/activity/{activity}/upload-status/{filename}', [
        'as'   => 'import.transaction.status',
        'uses' => 'ImportController@confirmTransactionsImport'
    ]);

    $router->get('/confirm-transactions-import', [
        'as'   => 'import.confirm-transactions',
        'uses' => 'ImportController@getUploadedTransactionRows'
    ]);

    $router->post('/import-valid-transactions/{activityId}', [
        'as'   => 'import.save-valid-transactions',
        'uses' => 'ImportController@saveValidTransactions'
    ]);
});
