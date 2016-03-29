<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;

/**
 * Class ParticipatingOrganization
 * @package App\Core\tz\Forms
 */
class ParticipatingOrganization extends BaseForm
{
    /**
     * builds activity participating organization form
     */
    public function buildForm()
    {
        $this
            ->addCollection('funding_organization', 'OrganizationName', 'funding_organization', ['label' => 'Funding Organization'], false, 'tz')
            ->addAddMoreButton('add_funding_organization', 'funding_organization')
            ->addCollection('implementing_organization', 'OrganizationName', 'implementing_organization', ['label' => 'Implementing Organization'], false, 'tz')
            ->addAddMoreButton('add_implementing_organization', 'implementing_organization');
    }
}
