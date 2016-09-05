<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Models\Activity\Activity;
use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\IatiElement;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

/**
 * Class Identifier
 * @package App\Services\CsvImporter\Entities\Activity\Components\Elements
 */
class Identifier extends IatiElement
{
    /**
     * CSV Header of Description with their code
     */
    private $_csvHeader = ['activity_identifier'];

    /**
     * @var array
     */
    protected $template = [['activity_identifier' => '', 'iati_identifier_text' => '']];

    /**
     * Description constructor.
     * @param                   $fields
     * @param Validation        $factory
     */
    public function __construct($fields, Validation $factory)
    {
        $this->prepare($fields);
        $this->factory = $factory;
    }

    /**
     * Prepare the Identifier element.
     * @param $fields
     */
    protected function prepare($fields)
    {
        foreach ($fields as $key => $values) {
            if (!is_null($values) && array_key_exists($key, array_flip($this->_csvHeader))) {
                foreach ($values as $value) {
                    $this->map($value);
                }
            }
        }
    }

    /**
     * Map data from CSV file into Identifier data format.
     * @param $value
     */
    public function map($value)
    {
        if (!is_null($value)) {
            $this->data[end($this->_csvHeader)] = $value;
            $this->data['iati_identifier_text'] = '';
        }
    }

    /**
     * Validate data for Identifier.
     */
    public function validate()
    {
        $this->validator = $this->factory->sign($this->data())
                                         ->with($this->rules(), $this->messages())
                                         ->getValidatorInstance();

        $this->setValidity();
    }

    /**
     * Set the validity for the Identifier data.
     */
    protected function setValidity()
    {
        $this->isValid = $this->validator->passes();
    }

    /**
     * Provides the rules for the Identifier validation.
     * @return array
     */
    public function rules()
    {
        return [
            'activity_identifier' => sprintf('required|not_in:%s', implode(',', $this->activityIdentifiers()))
        ];
    }

    /**
     * Provides custom messages used for Identifier Validation.
     * @return array
     */
    public function messages()
    {
        return [
            'activity_identifier.required' => 'Activity Identifier is required.',
            'activity_identifier.not_in'   => 'Activity Identifier should be unique.'
        ];
    }

    /**
     * Get all Activity Identifiers present until now.
     * @return array
     */
    protected function activityIdentifiers()
    {
        $identifiers = [];

        foreach ($this->activities() as $activity) {
            $identifiers[] = getVal($activity->identifier, ['activity_identifier']);
        }

        return $identifiers;
    }

    /**
     * Get all the Activities.
     * @return mixed
     */
    protected function activities()
    {
        return app()->make(Activity::class)->query()->get(['identifier']);
    }
}
