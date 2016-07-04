<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;
use App\Services\Import\Validators\Transaction\TransactionCsvValidator;

/**
 * Class CsvImportValidator
 * @package App\Services\RequestManager\Activity
 */
class CsvImportValidator
{
    public $validator;

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        $this->validator = $version->getActivityElement()->getCsvImportValidator();
    }

    /**
     * Returns the TransactionCsvValidator Instance.
     * @return mixed
     */
    public function getTransactionImportValidator()
    {
        return app()->make(TransactionCsvValidator::class);
    }
}
