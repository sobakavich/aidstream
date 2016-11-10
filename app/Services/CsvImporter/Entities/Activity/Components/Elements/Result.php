<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Elements;

use App\Services\CsvImporter\Entities\Activity\Components\Elements\Foundation\Iati\Element;
use App\Services\CsvImporter\Entities\Activity\Components\Factory\Validation;

class Result extends Element
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $index = 'result';

    /**
     * @var array
     */
    protected $result;

    /**
     * Result constructor.
     * @param            $resultRow
     * @param Validation $factory
     */
    public function __construct($resultRow, Validation $factory)
    {
        $this->prepare($resultRow);
        $this->factory = $factory;
    }

    /**
     * Prepare the IATI Element.
     * @param $fields
     */
    protected function prepare($fields)
    {
        $this->loadTemplate();
        // TODO: Implement prepare() method.
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

    protected function loadTemplate()
    {
        dd(json_decode(file_get_contents(app_path('Services/CsvImporter/Entities/Activity/Components/Elements/Foundation/Template/Result.json')), true));
    }
}