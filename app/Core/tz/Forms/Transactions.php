<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;

/**
 * Class Transactions
 * @package App\Core\tz\Forms
 */
class Transactions extends BaseForm
{
    public function buildForm()
    {
        return $this
            ->addCollection('transaction', 'Transaction', 'transaction', [], false, 'tz')
            ->addAddMoreButton('add_transaction', 'transaction');
    }
}
