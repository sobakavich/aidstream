<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class PeriodEnd
 * @package App\Core\V201\Forms\Activity
 */
class PeriodEnd extends BaseForm
{
    public function buildForm()
    {
        $this->add('date', 'date', ['help_block' => $this->addHelpText('Activity_Budget_PeriodEnd-iso_date'), 'required' => true, 'attr' => ['placeholder' => 'YYYY-MM-DD']]);
    }
}
