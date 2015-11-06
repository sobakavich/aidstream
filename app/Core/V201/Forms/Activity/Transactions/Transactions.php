<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Transactions
 * @package App\Core\V201\Forms\Activity
 */
class Transactions extends BaseForm
{
    /**
     * builds activity Transaction form
     */
    public function buildForm()
    {
        $this
            ->addCollection('transaction', 'Activity\Transactions\Transaction');
    }
}
