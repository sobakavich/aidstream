<?php namespace App\Core\V201\Requests\Activity;

use Illuminate\Support\Facades\Validator;

/**
 * Class ImportResult
 * @package App\Core\V201\Requests\Activity
 */
class ImportResult extends ActivityBaseRequest
{
    function __construct()
    {
        Validator::extend(
            'result_file',
            function ($attribute, $value, $parameters, $validator) {
                $mimes    = ['text/csv'];
                $fileMime = $value->getClientMimeType();

                return in_array($fileMime, $mimes);
            }
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules             = [];
        $rules['result'][] = 'required';
        if ($this->file('result')) {
            $rules['result'][] = 'result_file';
        }

        return $rules;
    }

    /**
     * prepare error message
     * @return mixed
     */
    public function messages()
    {
        $messages['result.result_file'] = 'The result must be a file of type: csv.';

        return $messages;
    }
}
