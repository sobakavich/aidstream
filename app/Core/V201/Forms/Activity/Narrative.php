<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use App\Core\Traits\LanguageCodeList;

/**
 * Class Narrative
 * @package App\Core\V201\Forms\Activity
 */
class Narrative extends BaseForm
{
    use LanguageCodeList;

    protected $showFieldErrors = true;

    /**
     * builds the narrative form
     */
    public function buildForm()
    {
        $this
            ->add('narrative', 'text', ['label' => $this->getData('label'), 'rules' => 'required'])
            ->add(
                'language',
                'select',
                [
                    'choices' => $this->getLanguageCodeList('Language', 'Activity'),
                    'label'   => 'Language'
                ]
            )
            ->addRemoveThisButton('remove_from_collection');

    }
}
