<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;

/**
 * Class Sector
 * @package App\Core\V201\Forms\Activity
 */
class Sector extends BaseForm
{
    /**
     * builds the activity sector form
     */
    public function buildForm()
    {
        $this
            ->add(
                'sector_code',
                'select',
                [
                    'choices' => $this->getCodeList('Sector', 'Activity'),
                    'attr'    => ['class' => 'form-control sector_code']
                ]
            )
            ->add(
                'sector_vocabulary',
                'select',
                [
                    'choices' => $this->getCodeList('SectorVocabulary', 'Activity'),
                    'attr'    => ['class' => 'form-control sector_vocabulary']
                ]
            )
            ->addNarrative('sector_narrative')
            ->addAddMoreButton('add', 'sector_narrative');
    }
}
