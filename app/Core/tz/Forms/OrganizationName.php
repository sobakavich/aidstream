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
        $this
            ->add('organization_name', 'textarea')
            ->addRemoveThisButton('remove_participating_org');
    }
}
