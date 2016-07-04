<?php namespace App\Services\Import\Validators\Transaction;

use App\Core\V201\Traits\GetCodes;
use App\Services\Queue\Validators\Transaction\Traits\RegistersValidators;

class SimpleTransactionValidator
{
    use GetCodes;
    
    public function __construct()
    {
        
    }

    public function validate(array $row)
    {
        dd($row);
    }
}
