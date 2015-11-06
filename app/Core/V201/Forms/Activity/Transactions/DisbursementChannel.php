<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class DisbursementChannel
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class DisbursementChannel extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Transaction Disbursement Channel form
     */
    public function buildForm()
    {
        $this
            ->add(
                'disbursement_channel_code',
                'select',
                [
                    'choices' => $this->getCodeList('DisbursementChannel', 'Activity'),
                    'attr'    => ['class' => 'form-control disbursement_channel'],
                ]
            );
    }

}
