<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class FlowType
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class FlowType extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds flow type form
     */
    public function buildForm()
    {
        $this
            ->add(
                'flow_type',
                'select',
                [
                    'choices' => $this->getCodeList('FlowType', 'Activity'),
                    'attr'    => ['class' => 'form-control flow_type']
                ]
            );
    }

}
