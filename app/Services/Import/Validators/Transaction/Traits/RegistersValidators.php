<?php namespace App\Services\Queue\Validators\Transaction\Traits;


use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class RegistersValidators
 * @package App\Services\Queue\Validators\Transaction\Traits
 */
trait RegistersValidators
{
    /**
     * Register Validators.
     */
    public function registerValidators()
    {
        $this->applyMultipleValueValidation();
        $this->applyRequiredAnyValidation();
        $this->applyRequiredOnlyOneValidation();
        $this->applyUniqueValidation();
    }

    /**
     *
     */
    protected function applyMultipleValueValidation()
    {
        Validator::extend(
            'multiple_value_in',
            function ($attribute, $value, $parameters, $validator) {
                $inputs = explode(';', $value);
                foreach ($inputs as $input) {
                    if (!in_array($input, $parameters)) {
                        return false;
                    }
                }

                return true;
            }
        );    
    }

    /**
     *
     */
    protected function applyRequiredAnyValidation()
    {
        Validator::extendImplicit(
            'required_any',
            function ($attribute, $value, $parameters, $validator) {
                for ($i = 1; $i < count($parameters); $i = $i + 2) {
                    $values = $parameters[$i];
                    if (!empty($values)) {
                        return true;
                    }
                }

                return false;
            }
        );    
    }

    /**
     *
     */
    protected function applyRequiredOnlyOneValidation()
    {
        Validator::extendImplicit(
            'required_only_one',
            function ($attribute, $value, $parameters, $validator) {
                $counter = 0;
                foreach ($parameters as $parameterIndex => $parameter) {
                    if (($parameterIndex % 2 != 0) && (!empty($parameter))) {
                        $counter ++;
                    }
                }

                if ($counter == self::REQUIRED_NONEMPTY_FIELD) {
                    return true;
                }

                return false;
            }
        );    
    }

    /**
     *
     */
    protected function applyUniqueValidation()
    {
        Validator::extendImplicit(
            'unique_validation',
            function ($attribute, $value, $parameters, $validator) {
                $csvDatas = Excel::load($parameters[2])->get()->toArray();
                $counter  = 0;
                $csvFiled = $parameters[3];
                foreach ($csvDatas as $csvDataIndex => $csvData) {
                    if ($csvData[$csvFiled] == $parameters[1]) {
                        $counter ++;
                    }
                }

                if ($counter == self::IDENTICAL_INTERNAL_REFERENCE) {
                    return true;
                }

                return false;
            }
        );    
    }
}
