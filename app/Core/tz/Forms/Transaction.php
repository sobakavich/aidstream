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
            ->add('date', 'date', ['required' => true, 'attr' => ['placeholder' => 'YYYY-MM-DD']])
            ->add('amount', 'text', ['required' => true])
            ->add('narrative', 'textarea', ['required' => true])
            ->addRemoveThisButton('remove_transaction');
    }
}
