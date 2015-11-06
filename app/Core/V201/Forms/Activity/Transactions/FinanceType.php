<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class FinanceType
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class FinanceType extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds finance type form
     */
    public function buildForm()
    {
        $this
            ->add(
                'finance_type',
                'select',
                [
                    'choices' => $this->getCodeList('FinanceType', 'Activity'),
                    'attr'    => ['class' => 'form-control finance_type']
                ]
            );
    }

}
