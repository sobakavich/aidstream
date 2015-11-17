<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Results
 * @package App\Core\V201\Forms\Activity
 */
class Results extends BaseForm
{
    /**
     * builds the Activity Result form
     */
    public function buildForm()
    {
        $this
            ->addCollection('result', 'Activity\Result', 'result');
    }
}
