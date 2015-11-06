<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class TiedStatus
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class TiedStatus extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds tied status code form
     */
    public function buildForm()
    {
        $this
            ->add(
                'tied_status_code',
                'select',
                [
                    'choices' => $this->getCodeList('TiedStatus', 'Activity'),
                    'attr'    => ['class' => 'form-control tied_status_code']
                ]
            );
    }

}
