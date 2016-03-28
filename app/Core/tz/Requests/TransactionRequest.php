<?php namespace App\Core\tz\Requests;

use App\Http\Requests\Request;

/**
 * Class TransactionRequest
 * @package App\Core\tz\Requests
 */
class TransactionRequest extends Request
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules        = [];
        $transactions = $this->get('transaction');
        foreach ($transactions as $transactionIndex => $transaction) {
            $formBase                                  = sprintf('transaction.%s', $transactionIndex);
            $rules[sprintf('%s.reference', $formBase)] = 'required';
            $rules[sprintf('%s.date', $formBase)]      = 'required|date';
            $rules[sprintf('%s.amount', $formBase)]    = 'required|numeric|min:0';
            $rules[sprintf('%s.narrative', $formBase)] = 'required';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages     = [];
        $transactions = $this->get('transaction');
        foreach ($transactions as $transactionIndex => $transaction) {
            $formBase                                              = sprintf('transaction.%s', $transactionIndex);
            $messages[sprintf('%s.reference.required', $formBase)] = 'Reference is required.';
            $messages[sprintf('%s.date.required', $formBase)]      = 'Date is required';
            $messages[sprintf('%s.date.date', $formBase)]          = 'Date must be in correct format';
            $messages[sprintf('%s.amount.required', $formBase)]    = 'Amount is required';
            $messages[sprintf('%s.amount.numeric', $formBase)]     = 'Amount must be number';
            $messages[sprintf('%s.amount.min', $formBase)]         = 'Amount must be greater than 0';
            $messages[sprintf('%s.narrative.required', $formBase)] = 'Narrative is required';
        }

        return $messages;
    }
}
