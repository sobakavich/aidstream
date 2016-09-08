<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class ParticipatingOrganisation
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class ParticipatingOrganisation extends Element
{
    /**
     * @var array
     */
    private $_csvHeaders = ['participating_organisation_role', 'participating_organisation_type', 'participating_organisation_name', 'participating_organisation_identifier'];

    /**
     * @var array
     */
    protected $template = ['type' => '', 'date' => '', 'narrative' => ['narrative' => '', 'language' => '']];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var
     */
    protected $narratives;

    /**
     * @var array
     */
    protected $orgRoles = [];

    /**
     * ParticipatingOrganisation constructor.
     * @param            $fields
     * @param Validation $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare ParticipatingOrganisation Element.
     * @param $fields
     */
    public function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values)) {
                foreach ($values as $index => $value) {
                    $this->map($key, $value, $index);
                }
            }
        }
    }

    /**
     * Map data from CSV file into ParticipatingOrganisation data format.
     * @param $key
     * @param $value
     * @param $index
     */
    public function map($key, $value, $index)
    {
        if (array_key_exists($key, array_flip($this->_csvHeaders))) {
            $this->setOrganisationRole($key, $value, $index);
            $this->setIdentifier($key, $value, $index);
            $this->setOrganisationType($key, $value, $index);
            $this->data['participating_organization'][$index]['activity_id'] = '';
            $this->setNarrative($key, $value, $index);
        }

    }

    /**
     * Set Organisation Role of Participating Organisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setOrganisationRole($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['organization_role'])) {
            $this->data['participating_organization'][$index]['organization_role'] = '';
        }

        if ($key == $this->_csvHeaders[0] && (!is_null($value))) {
            $this->orgRoles[] = $value;
            $this->orgRoles   = array_unique($this->orgRoles);

            $this->data['participating_organization'][$index]['organization_role'] = $value;
        }
    }

    /**
     * Set Identifier of Participating Organisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setIdentifier($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['identifier'])) {
            $this->data['participating_organization'][$index]['identifier'] = '';
        }

        if ($key == $this->_csvHeaders[3] && (!is_null($value))) {
            $this->data['participating_organization'][$index]['identifier'] = $value;
        }

    }

    /**
     * Set OrganisationType for Participating Organisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setOrganisationType($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['organization_type'])) {
            $this->data['participating_organization'][$index]['organization_type'] = '';
        }

        if ($key == $this->_csvHeaders[1] && (!is_null($value))) {
            $this->types[] = $value;
            $this->types   = array_unique($this->types);

            $this->data['participating_organization'][$index]['organization_type'] = $value;
        }
    }

    /**
     * Set Narrative for ParticipatingOrganisation.
     * @param $key
     * @param $value
     * @param $index
     */
    protected function setNarrative($key, $value, $index)
    {
        if (!isset($this->data['participating_organization'][$index]['narrative'])) {
            $this->data['participating_organization'][$index]['narrative'][] = ['narrative' => '', 'language' => ''];
        } else {
            if ($key == $this->_csvHeaders[2]) {
                foreach ($this->data['participating_organization'][$index]['narrative'] as $d) {
                    $this->data['participating_organization'][$index]['narrative'] = array_filter($d);
                }

                $narrative          = ['narrative' => $value, 'language' => ''];
                $this->narratives[] = $narrative;

                $this->data['participating_organization'][$index]['narrative'][] = $narrative;
            }
        }
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        return [
            'participating_organization'                     => 'required|required_only_one_among:identifier,narrative',
            'participating_organization.*.organization_role' => sprintf('required|in:%s', $this->validOrganizationRoles()),
        ];
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        return [
            'participating_organization.required'                     => 'Participating Organisation is required.',
            'participating_organization.*.organization_role.required' => 'Participating Organisation role is required.',
            'participating_organization.required_only_one_among'      => 'Either Participating Organisation Identifier or Participating Organisation Name is required.',
            'participating_organization.*.organization_role.in'       => 'Only valid Organisation Roles are allowed.'
        ];
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        $this->validator = $this->factory->sign($this->data())
                                         ->with($this->rules(), $this->messages())
                                         ->getValidatorInstance();

        $this->setValidity();
    }

    /**
     * Get the valid OrganizationRole from the OrganizationRole codelist as a string.
     * @return string
     */
    protected function validOrganizationRoles()
    {
        list($organizationRoleCodeList, $organizationRoles) = [$this->loadCodeList('OrganisationRole', 'V201'), []];

        array_walk(
            $organizationRoleCodeList['OrganisationRole'],
            function ($organizationRole) use (&$organizationRoles) {
                $organizationRoles[] = $organizationRole['code'];
                $organizationRoles[] = $organizationRole['name'];
            }
        );

        return implode(',', array_keys(array_flip($organizationRoles)));
    }
}
