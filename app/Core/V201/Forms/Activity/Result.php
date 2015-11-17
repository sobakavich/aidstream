<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Result
 * @package App\Core\V201\Forms\Activity
 */
class Result extends BaseForm
{
    /**
     * builds the Activity Result form
     */
    public function buildForm()
    {
        $this
            ->add(
                'type',
                'select',
                ['choices' => $this->getCodeList('ResultType', 'Activity')]
            )
            ->add(
                'aggregation_status',
                'select',
                ['choices' => [0 => 'False', 1 => 'True']]
            )
            ->addTitleCollection()
            ->addTitleCollection('description')
            ->addCollection('indicator', 'Activity\Indicator', 'indicator')
            ->addAddMoreButton('add_indicator', 'indicator');
    }
}
