<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Target
 * Contains the function to create the target form
 * @package App\Core\V201\Forms\Activity
 */
class Target extends BaseForm
{
    /**
     * builds the activity target form
     */
    public function buildForm()
    {
        $this
            ->add('value', 'text')
            ->addTitleCollection('comment');
    }
}
