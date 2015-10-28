<?php namespace App\Core\V201\Requests\Activity;


/**
 * Class OtherIdentifierRequest
 * @package App\Core\V201\Requests\Activity
 */
class OtherIdentifierRequest extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForOtherIdentifier($this->request->get('other_identifier'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForOtherIdentifier($this->request->get('other_identifier'));
    }


    /**
     * @param array $formFields
     * @return array
     */
    public function addRulesForOtherIdentifier(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $otherIdentifierIndex => $otherIdentifier) {
            $otherIdentifierForm                                  = sprintf(
                'other_identifier.%s',
                $otherIdentifierIndex
            );
            $rules[sprintf('%s.reference', $otherIdentifierForm)] = 'required';
            $rules[sprintf('%s.type', $otherIdentifierForm)]      = 'required';
            $rules                                                = array_merge(
                $rules,
                $this->addRulesForOwnerOrg($otherIdentifier['owner_org'], $otherIdentifierForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addMessagesForOtherIdentifier(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $otherIdentifierIndex => $otherIdentifier) {
            $otherIdentifierForm                                              = sprintf(
                'other_identifier.%s',
                $otherIdentifierIndex
            );
            $messages[sprintf('%s.reference.required', $otherIdentifierForm)] = 'Reference is required';
            $messages[sprintf('%s.type.required', $otherIdentifierForm)]      = 'Type is required';
            $messages                                                         = array_merge(
                $messages,
                $this->addMessagesForOwnerOrg($otherIdentifier['owner_org'], $otherIdentifierForm)
            );

        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForOwnerOrg($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $ownerOrgIndex => $ownerOrg) {
            $ownerOrgForm                                  = sprintf('%s.owner_org.%s', $formBase, $ownerOrgIndex);
            $rules[sprintf('%s.reference', $ownerOrgForm)] = 'required';
            $rules                                         = array_merge(
                $rules,
                $this->addRulesForNarrative($ownerOrg['narrative'], $ownerOrgForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForOwnerOrg($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $ownerOrgIndex => $ownerOrg) {
            $ownerOrgForm                                              = sprintf(
                '%s.owner_org.%s',
                $formBase,
                $ownerOrgIndex
            );
            $messages[sprintf('%s.reference.required', $ownerOrgForm)] = 'Reference is required';
            $messages                                                  = array_merge(
                $messages,
                $this->addMessagesForNarrative($ownerOrg['narrative'], $ownerOrgForm)
            );
        }

        return $messages;
    }

}
