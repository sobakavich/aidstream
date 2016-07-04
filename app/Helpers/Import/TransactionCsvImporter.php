<?php

use App\Exceptions\Aidstream\Import\HeaderMisMatchException;
use App\Models\Activity\Activity;
use App\Services\Activity\UploadTransactionManager;
use App\Services\Import\Validators\Transaction\DetailedTransactionValidator;
use App\Services\Import\Validators\Transaction\SimpleTransactionValidator;
use App\Services\RequestManager\Activity\CsvImportValidator;
use Illuminate\Support\Facades\File;

function import(Activity $activity, $rows, $filePath, $filename, $version)
{
    $transactionRepository = getTransactionRepository($version);
    $transactionDetails    = [];
    $validator             = validatorByCsvType($filePath);
    $activityId            = $activity->id;

    foreach ($rows as $index => $value) {
        $row = $transactionRepository->formatFromExcelRow($value);

        if ($validator->validate($value, $activityId)->fails()) {
            dd($validator->validate($value, $activityId), $value);
            dd('ss');
        }
        $transactionDetails[] = $row;
        saveMetaData($row, $activityId, $filename, $index);
    }

//    $references = $transactionRepository->getTransactionReferences($activity->id);
//
//    foreach ($transactionDetails as $transactionDetail) {
//        $transactionReference = $transactionDetail['reference'];
//        if (isset($references[$transactionReference])) {
//            $transactionRepository->update($transactionDetail, $references[$transactionReference]);
//        } else {
//            $transactionRepository->upload($transactionDetail, $activity);
//        }
//    }
}

/**
 * Returns a Validator for the current Csv Type.
 * @param                    $filePath
 * @return mixed
 * @throws HeaderMisMatchException
 */
function validatorByCsvType($filePath)
{
    try {
        $transactionManager = app()->make(UploadTransactionManager::class);

        if ($transactionManager->isSimpleCsv($filePath)) {
            return app()->make(SimpleTransactionValidator::class);
        }

        return app()->make(DetailedTransactionValidator::class);
    } catch (HeaderMisMatchException $exception) {
        return null;
    }
}

function saveMetaData($details, $activityId, $filename, $index, $validity = 1)
{
    $filePath = sprintf('%s%s', config('filesystems.queuedFileMetaDataPath'), 'csvMetaData.json');
    $metaData = [];
    $key      = $activityId . '_' . ($index + 1);

    if (!file_exists(config('filesystems.queuedFileMetaDataPath'))) {
        mkdir(config('filesystems.queuedFileMetaDataPath'));
    }

    if (file_exists($filePath)) {
        $metaData = json_decode(file_get_contents($filePath), true);
    }

    $metaData[$filename]['activity_id']                    = $activityId;
    $metaData[$filename]['transactions'][$key]['data']     = $details;
    $metaData[$filename]['transactions'][$key]['validity'] = $validity;
    $metaData[$filename]['transactions'][$key]['status']   = 'written';

    File::put($filePath, json_encode($metaData));
}

/**
 * Returns a CsvImportValidator Instance.
 * @return mixed
 */
function getCsvImportValidator()
{
    return app()->make(CsvImportValidator::class);
}

/**
 * Returns an UploadTransaction instance according to the current IATI Version.
 * @param $version
 * @return mixed
 */
function getTransactionRepository($version)
{
    return app()->make(sprintf('%s%s\Repositories\Activity\UploadTransaction', "App\\Core\\", $version));
}
