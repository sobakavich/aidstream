<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Indicator
 * Contains the function to create the indicator form
 * @package App\Core\V201\Forms\Activity
 */
class Indicator extends BaseForm
{
    /**
     * builds the activity indicator form
     */
    public function buildForm()
    {
        $this
            ->add(
                'measure',
                'select',
                ['choices' => $this->getCodeList('IndicatorMeasure', 'Activity')]
            )
            ->add(
                'ascending',
                'select',
                ['choices' => [0 => 'False', 1 => 'True']]
            )
            ->addTitleCollection()
            ->addTitleCollection('description')
            ->addCollection('baseline', 'Activity\Baseline')
            ->addCollection('period', 'Activity\Period')
            ->addRemoveThisButton('remove_indicator');
    }
}
