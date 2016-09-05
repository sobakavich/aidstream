<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Factory;

use Illuminate\Validation\Factory;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ValidationFactory
 * @package App\Services\CsvImporter\Entities\Activity\Components\Validators
 */
class Validation extends Factory
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Rules for the validation.
     * @var array
     */
    protected $rules = [];

    /**
     * Messages for failed validation rules.
     * @var array
     */
    protected $messages = [];

    /**
     * ValidationFactory constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator);
    }

    /**
     * Set the data to be validated.
     * @param $data
     * @return $this
     */
    public function sign($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Append rules and messages for the Validator.
     * @param array $rules
     * @param array $messages
     * @return $this
     */
    public function with(array $rules = [], array $messages = [])
    {
        $this->rules    = $rules;
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get the Validator instance for the data to be validated with the current rules and messages.
     * @return \Illuminate\Validation\Validator
     */
    public function getValidatorInstance()
    {
        return $this->make($this->data, $this->rules, $this->messages);
    }
}
