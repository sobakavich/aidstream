<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;

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
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->prepare($fields);
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
            $this->data[$index]['activity_id'] = '';
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
        if (!isset($this->data[$index]['organization_role'])) {
            $this->data[$index]['organization_role'] = '';
        }

        if ($key == $this->_csvHeaders[0] && (!is_null($value))) {
            $this->orgRoles[] = $value;
            $this->orgRoles   = array_unique($this->orgRoles);

            $this->data[$index]['organization_role'] = $value;
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
        if (!isset($this->data[$index]['identifier'])) {
            $this->data[$index]['identifier'] = '';
        }

        if ($key == $this->_csvHeaders[3] && (!is_null($value))) {
            $this->data[$index]['identifier'] = $value;
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
        if (!isset($this->data[$index]['organization_type'])) {
            $this->data[$index]['organization_type'] = '';
        }

        if ($key == $this->_csvHeaders[1] && (!is_null($value))) {
            $this->types[] = $value;
            $this->types   = array_unique($this->types);

            $this->data[$index]['organization_type'] = $value;
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
        if (!isset($this->data[$index]['narrative'])) {
            $this->data[$index]['narrative'] = ['narrative' => '', 'language' => ''];
        }

        if ($key == $this->_csvHeaders[2]) {
            $narrative          = ['narrative' => $value, 'language' => ''];
            $this->narratives[] = $narrative;

            $this->data[$index]['narrative'] = $narrative;
        }
    }

    /**
     * Provides the rules for the IATI Element validation.
     * @return array
     */
    public function rules()
    {
        // TODO: Implement rules() method.
    }

    /**
     * Provides custom messages used for IATI Element Validation.
     * @return array
     */
    public function messages()
    {
        // TODO: Implement messages() method.
    }

    /**
     * Validate data for IATI Element.
     */
    public function validate()
    {
        // TODO: Implement validate() method.
    }
}
