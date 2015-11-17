<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Baseline
 * Contains the function to create the title form
 * @package App\Core\V201\Forms\Activity
 */
class Baseline extends BaseForm
{
    /**
     * builds the activity title form
     */
    public function buildForm()
    {
        $this
            ->add('year', 'text')
            ->add('value', 'text')
            ->addTitleCollection('comment');
    }
}
