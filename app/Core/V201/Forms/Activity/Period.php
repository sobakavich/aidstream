<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Period
 * Contains the function to create the period form
 * @package App\Core\V201\Forms\Activity
 */
class Period extends BaseForm
{
    /**
     * builds the activity period form
     */
    public function buildForm()
    {
        $this
            ->addCollection('period_start', 'Activity\PeriodDate')
            ->addCollection('period_end', 'Activity\PeriodDate')
            ->addCollection('target', 'Activity\Target')
            ->addCollection('actual', 'Activity\Target');
    }
}
