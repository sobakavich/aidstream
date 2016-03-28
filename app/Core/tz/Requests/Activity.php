<?php namespace App\Core\tz\Requests;

use App\Core\V201\Repositories\Activity\IatiIdentifierRepository;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class Activity
 * @package App\Core\V201\Requests\Activity
 */
class Activity extends Request
{
    function __construct()
    {
        Validator::extend(
            'exclude_operators',
            function ($attribute, $value, $parameters, $validator) {
                return !preg_match('/[\/\&\|\?|]+/', $value);
            }
        );
        Validator::extend(
            'participating_org',
            function ($attribute, $value, $parameters, $validator) {
                removeEmptyValues($value);

                return $value;
            }
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForActivity($this->get('activity'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForActivity($this->get('activity'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForActivity(array $formFields)
    {
        $rules                    = [];
        $iatiIdentifierRepository = app(IatiIdentifierRepository::class);
        $activityIdentifiers      = [];
        $activityId               = $this->get('id');
        $identifiers              = ($activityId) ? $iatiIdentifierRepository->getActivityIdentifiersForOrganizationExcept(
            $activityId
        ) : $iatiIdentifierRepository->getIdentifiersForOrganization();

        foreach ($identifiers as $identifier) {
            $activityIdentifiers[] = $identifier->activity_identifier;
        }

        $activityIdentifier = implode(',', $activityIdentifiers);

        foreach ($formFields as $activityIndex => $activity) {
            $activityForm                                                                = sprintf('activity.%s', $activityIndex);
            $rules[sprintf('%s.iati_identifiers.0.activity_identifier', $activityForm)]  = 'required|exclude_operators|not_in:' . $activityIdentifier;
            $rules[sprintf('%s.iati_identifiers.0.iati_identifier_text', $activityForm)] = 'required';
            $rules[sprintf('%s.title', $activityForm)]                                   = 'required';
            $rules[sprintf('%s.description.0.general', $activityForm)]                   = 'required';
            $rules[sprintf('%s.participating_organization.0', $activityForm)]            = 'participating_org';
            $rules[sprintf('%s.activity_status', $activityForm)]                         = 'required';
            $rules[sprintf('%s.sector_category_code', $activityForm)]                    = 'required';
            $rules[sprintf('%s.start_date', $activityForm)]                              = 'required';
            $rules[sprintf('%s.recipient_country', $activityForm)]                       = 'required';
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getMessagesForActivity(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $activityIndex => $activity) {
            $activityForm                                                                            = sprintf('activity.%s', $activityIndex);
            $messages[sprintf('%s.iati_identifiers.0.activity_identifier.required', $activityForm)]  = 'Activity Identifier is required.';
            $messages[sprintf('%s.iati_identifiers.0.activity_identifier.not_in', $activityForm)]    = 'The selected activity identifier is invalid and must be unique.';
            $messages[sprintf('%s.iati_identifiers.0.iati_identifier_text.required', $activityForm)] = 'IATI Identifier is required.';
            $messages[sprintf('%s.title.required', $activityForm)]                                   = 'Title is required.';
            $messages[sprintf('%s.description.0.general.required', $activityForm)]                   = 'General Description is required.';
            $messages[sprintf('%s.participating_organization.0.participating_org', $activityForm)]   = 'At least one Funding/Implementing Organization is required.';
            $messages[sprintf('%s.activity_status.required', $activityForm)]                         = 'Activity Status is required.';
            $messages[sprintf('%s.sector_category_code.required', $activityForm)]                    = 'Sector is required.';
            $messages[sprintf('%s.start_date.required', $activityForm)]                              = 'Start Date is required.';
            $messages[sprintf('%s.recipient_country.required', $activityForm)]                       = 'Recipient Country is required.';
        }

        return $messages;
    }
}
