<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class ActivityBaseRequest
 * common validation rules and messages
 * @package App\Core\V201\Requests\Activity
 */
class ActivityBaseRequest extends Request
{
    function __construct()
    {
        Validator::extendImplicit(
            'unique_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages = [];
                foreach ($value as $narrative) {
                    $language = $narrative['language'];
                    if (in_array($language, $languages)) {
                        return false;
                    }
                    $languages[] = $language;
                }

                return true;
            }
        );

        Validator::extendImplicit(
            'required_with_language',
            function ($attribute, $value, $parameters, $validator) {
                $language = preg_replace('/([^~]+).narrative/', '$1.language', $attribute);

                return !(Input::get($language) && !Input::get($attribute));
            }
        );
    }

    /**
     * returns rules for narrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForNarrative($formFields, $formBase, $required = false)
    {
        $rules                                     = [];
        $rules[sprintf('%s.narrative', $formBase)] = 'unique_lang';
        $narrativeRules                            = [];
        !$required ?: $narrativeRules[] = 'required';
        $narrativeRules[] = 'required_with_language';
        $narrativeRules   = implode('|', $narrativeRules);
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)] = $narrativeRules;
        }

        return $rules;
    }

    /**
     * returns messages for narrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForNarrative($formFields, $formBase, $required = false)
    {
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = 'Languages should be unique.';
        foreach ($formFields as $narrativeIndex => $narrative) {
            !$required ?: $messages[sprintf('%s.narrative.%s.narrative.required', $formBase, $narrativeIndex)] = 'Narrative is required.';
            $messages[sprintf('%s.narrative.%s.narrative.required_with_language', $formBase, $narrativeIndex)] = 'Narrative is required with language.';
        }

        return $messages;
    }
}
