<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class AidType
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class AidType extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds aid type form
     */
    public function buildForm()
    {
        $this
            ->add(
                'aid_type',
                'select',
                [
                    'choices' => $this->getCodeList('AidType', 'Activity'),
                    'attr'    => ['class' => 'form-control aid_type']
                ]
            );
    }

}
