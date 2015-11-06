<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Type
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Type extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Transaction type form
     */
    public function buildForm()
    {
        $this
            ->add(
                'transaction_type_code',
                'select',
                [
                    'choices' => $this->getCodeList('TransactionType', 'Activity'),
                    'attr'    => ['class' => 'form-control transaction_type']
                ]
            );
    }

}
