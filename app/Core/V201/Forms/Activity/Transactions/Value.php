<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Value
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Value extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds Transaction value form
     */
    public function buildForm()
    {
        $this
            ->add('amount', 'text')
            ->add(
                'date',
                'date',
                [
                    'label' => 'Value Date'
                ]
            )
            ->add(
                'currency',
                'select',
                [
                    'choices' => $this->getCodeList('Currency', 'Organization'),
                    'attr'    => ['class' => 'form-control currency']
                ]
            );
    }

}
