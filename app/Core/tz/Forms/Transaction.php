<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;


/**
 * Class Transaction
 * @package App\Core\tz\Forms
 */
class Transaction extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('id', 'hidden')
            ->add('reference', 'text', ['required' => true])
            ->addCollection('date', 'Activity\Transactions\Date')
            ->add('amount', 'text', ['help_block' => $this->addHelpText('Activity_Transaction_Value-text'), 'required' => true])
            ->add(
                'narrative',
                'textarea',
                [
                    'label'      => $this->getData('label'),
                    'help_block' => $this->addHelpText($this->getData('help-text-narrative') ? $this->getData('help-text-narrative') : 'Narrative-text'),
                    'attr'       => ['rows' => 4],
                    'required'   => true
                ]
            )
            ->addRemoveThisButton('remove_transaction');
    }
}
