<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;

class Activity extends BaseForm
{
    /**
     * builds activity form
     */
    public function buildForm()
    {

        $this
            ->addCollection('iati_identifiers', 'Activity\Identifier','identifier-form', [], false)
            ->add('title', 'textarea', ['required' => true])
            ->addCollection('description', 'Description', '', [], false, 'tz')
            ->addCollection('participating_organization', 'ParticipatingOrganization', 'participation-org-form', [], false, 'tz')
            ->addSelect('activity_status', $this->getCodeList('ActivityStatus', 'Activity'), 'Activity status', $this->addHelpText('Activity_ActivityStatus-code'), null, true)
            ->addMultipleSelect('sector_category_code', $this->getCodeList('SectorCategory', 'Activity'), 'Sector', null, null, true)
            ->add('start_date', 'date', ['help_block' => $this->addHelpText('Activity_Budget_PeriodStart-iso_date'), 'required' => true])
            ->add('end_date', 'date', ['help_block' => $this->addHelpText('Activity_Budget_PeriodEnd-iso_date')])
            ->addMultipleSelect('recipient_country', $this->getCodeList('Country', 'Organization'), null, null, null, true);
    }
}
