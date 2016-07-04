<?php namespace App\Services\Import\Traits;


/**
 * Class ProvidesCsvMetaData
 * @package App\Services\Import\Traits
 */
trait ProvidesCsvMetaData
{
    /**
     * Get the selected transaction indices.
     * @param $transactionDetails
     * @return array
     */
    protected function selectedTransactions($transactionDetails)
    {
        $keys = [];

        foreach ($transactionDetails['transaction'] as $detail) {
            $keys[] = $detail;
        }

        return $keys;
    }

    /**
     * Get the file path for the Csv meta data file.
     */
    protected function getMetaDataFilePath()
    {
        return sprintf('%s%s', config('filesystems.queuedFileMetaDataPath'), self::TRANSACTION_CSV_METADATA_FILENAME);
    }

    /**
     * Get the meta data file contents.
     * @param $filePath
     * @return mixed
     */
    protected function getMetaData($filePath)
    {
        return json_decode(file_get_contents($filePath), true);
    }

    /**
     * Filter the selected transactions from total uploaded transactions.
     * @param $details
     * @param $selectedTransactions
     * @return array
     */
    protected function selectedTransactionData($details, $selectedTransactions)
    {
        $requiredData = [];

        foreach (getVal($details, ['transactions']) as $transactionIndex => $transactionDetail) {
            if (in_array($transactionIndex, $selectedTransactions)) {
                $requiredData[] = getVal($transactionDetail, ['data']);
            }
        }

        return $requiredData;
    }
}
