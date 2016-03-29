<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;

/**
 * Class OrganizationName
 * @package App\Core\tz\Forms
 */
class OrganizationName extends BaseForm
{
    /**
     * builds activity participating organization form
     */
    public function buildForm()
    {
        $label = $this->getData('label');
        $this
            ->add('organization_name', 'textarea', ['label' => $label])
            ->addRemoveThisButton('remove_participating_org');
    }
}
