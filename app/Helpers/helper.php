<?php
use App\Models\Activity\Activity;
use App\Models\Settings;
use App\User;

/**
 * removes empty values
 * @param $data
 */
function removeEmptyValues(&$data)
{
    foreach ($data as &$subData) {
        if (is_array($subData)) {
            removeEmptyValues($subData);
        }
    }
    $data = array_filter(
        $data,
        function ($value) {
            return ($value != '' && $value != []);
        }
    );
}

/**
 * trim an input
 * @param $input
 * @return string
 */
function trimInput($input)
{
    return trim(preg_replace('/\s+/', " ", $input));
}

/**
 * checks empty template or empty array
 * @param $input
 * @return bool
 */
function emptyOrHasEmptyTemplate($data)
{
    $temp = $data;
    removeEmptyValues($temp);

    return (!boolval($temp));
}

/**
 * get default currency which is predefined under activity defaults
 * @return null
 */
function getDefaultCurrency()
{
    if (request()->activity) {
        $defaultFieldValues = app(Activity::class)->find(request()->activity)->default_field_values;
    } else {
        $settings = app(Settings::class)->where('organization_id', session('org_id'))->first();
        if ($settings) {
            $defaultFieldValues = $settings->default_field_values;
        } else {
            return config('app.default_currency');
        }
    }

    $defaultCurrency = $defaultFieldValues ? $defaultFieldValues[0]['default_currency'] : null;

    return $defaultCurrency;
}

/**
 * get default language which is predefined under  activity defaults
 * @return null
 */
function getDefaultLanguage()
{
    if (request()->activity) {
        $defaultFieldValues = app(Activity::class)->find(request()->activity)->default_field_values;
    } else {
        $settings = app(Settings::class)->where('organization_id', session('org_id'))->first();
        if ($settings) {
            $defaultFieldValues = $settings->default_field_values;
        } else {
            return config('app.default_language');
        }
    }
    $defaultLanguage = $defaultFieldValues ? $defaultFieldValues[0]['default_language'] : null;

    return $defaultLanguage;
}

/**
 * Get the required index from a nested array.
 * @param        $arr
 * @param        $arguments
 * @param string $default
 * @return string
 */
function getVal(array $arr, array $arguments, $default = "")
{
    (!is_string($arguments)) ?: $arguments = explode('.', $arguments);
    if (is_array($arr)) {
        if (isset($arr[$arguments[0]]) && count(array_slice($arguments, 1)) === 0) {
            return $arr[$arguments[0]];
        } else {
            if (isset($arr[$arguments[0]]) && is_array($arr[$arguments[0]])) {
                $result = getVal($arr[$arguments[0]], array_slice($arguments, 1), $default);

                return $result ? $result : $default;
            } else {
                return $default;
            }
        }
    } else {
        if (isset($arr) && !is_array($arr)) {
            return $arr;
        } else {
            return $default;
        }
    }
}

/**
 * Checks if the request route contains prefix SuperAdmin.
 * @return bool
 */
function isSuperAdminRoute()
{
    $routeAction = request()->route()->getAction();

    return isset($routeAction['SuperAdmin']);
}

/**
 * Check if the logged in user is admin or user of any organization.
 * @param User $user
 * @return bool
 */
function isUserOrAdmin(User $user)
{
    if (!$user->isSuperAdmin() && !$user->isGroupAdmin()) {
        return true;
    }

    return false;
}

/**
 * Get the language name for the given language code.
 * @param $code
 * @return
 */
function getLanguage($code)
{
    $code ?: $code = getDefaultLanguage();
    $languages = json_decode(
        file_get_contents(app_path().config('filesystems.languages.v201.activity.language_codelist_path')),
        true
    );

    foreach ($languages['Language'] as $lang) {
        if ($lang['code'] === $code) {
            return $lang['name'];
        }
    }
}

/**
 * Get the language code for other than the first one.
 * @param array $language
 * @return array
 */
function getOtherLanguages(array $language)
{
    return array_slice($language, 1);
}

/**
 *
 * @param array $elements
 * @param       $type
 * @return collection
 */
function groupActivityElements(array $elements, $type)
{
    return collect($elements)->groupBy($type);
}

/**
 * Get Owner Narrative
 * @param array $groupedIdentifiers
 * @return string
 */
function getOwnerNarrative(array $groupedIdentifiers)
{
    return getVal($groupedIdentifiers, ['owner_org', 0, 'narrative'], []);
}

/**
 * Returns the first element of Narrative.
 * @param array $narrative
 * @return string
 */
function getFirstNarrative(array $narrative)
{
    $narrativeElements = $narrative['narrative'][0];

    return (empty($narrativeElements['narrative'])) ? '<em>Not available</em>' :
        sprintf(
            "%s <em>(language: %s)</em>",
            $narrativeElements['narrative'],
            getLanguage($narrativeElements['language'])
        );
}

/**
 * Returns the telephone number / email as string with commas after each other.
 * @param       $type
 * @param array $contactInformation
 * @return string
 */
function getContactInfo($type, array $contactInformation)
{
    $arrayContactInfo = [];

    foreach ($contactInformation as $information) {

        $information        = checkIfEmailOrWebSite($type, $information);
        $arrayContactInfo[] = $information;
    }
    $stringContactInfo = implode(' , ', $arrayContactInfo);

    return (empty($stringContactInfo)) ? '<em>Not Available</em>' : $stringContactInfo;
}

/**
 * Check if the provided contact information type is email or website.
 * @param $type
 * @param $information
 * @return string
 */
function checkIfEmailOrWebSite($type, $information)
{
    if ($type == "website" && !empty($information[$type])) {
        $information = getClickableLink($information[$type]);

    } else {
        if ($type == "email" && !empty($information[$type])) {
            $information = sprintf("<a href='mailto:%s'>%s</a>", $information[$type], $information[$type]);
        } else {
            $information = $information[$type];
        }
    }

    return $information;
}

/**
 * Checks if the provided value is empty or not. If empty returns not available.
 * @param $information
 * @return string
 */
function checkIfEmpty($information)
{
    return (empty($information)) ? '<em>Not Available</em>' : $information;
}

/**
 * Get Recipient Information
 *
 * @param       $code
 * @param       $percentage
 * @param       $type
 * @return string
 */
function getRecipientInformation($code, $percentage, $type)
{
    $method = ($type == 'Country') ? 'getOrganizationCodeName' : 'getActivityCodeName';
    $name   = app('App\Helpers\GetCodeName')->$method($type, $code);
    $name   = ucfirst(strtolower(substr($name, 0, -5)));

    return sprintf('%s - %s(%s%s)', $code, $name, $percentage, '%');
}

/**
 * Get the location reach code value.
 * @param array $locations
 * @return array
 */
function getLocationReach(array $locations)
{
    $newLocations = [];
    foreach ($locations as $location) {
        $code = $location['location_reach'][0]['code'];
        $code = app('App\Helpers\GetCodeName')->getCodeNameOnly(
            'GeographicLocationReach',
            $code
        );
        $code = ($code == "") ? 'Other' : sprintf('%s Location', $code);

        $newLocations[$code][] = $location;
    }

    return $newLocations;

}

/**
 * Get Location vocabularies
 * @param array $location
 * @return string
 */
function getLocationVocabularies(array $location)
{
    $vocabularyCode = $location['vocabulary'];
    $vocabulary     = app('App\Helpers\GetCodeName')->getActivityCodeName(
        'GeographicVocabulary',
        $vocabularyCode
    );

    $vocabularyValue = substr($vocabulary, 0, -4);

    return $vocabularyValue;
}

/**
 * Get the location ID vocabulary when the code is provided.
 * @param array $locationId
 * @return string
 */
function getLocationIdVocabulary(array $locationId)
{
    $vocabularyValue = getLocationVocabularies($locationId);

    if (empty($vocabularyValue)) {
        return '<em>Not Available</em>';
    } else {
        return sprintf('%s - %s (Code:%s)', $locationId['vocabulary'], $vocabularyValue, $locationId['code']);
    }
}

/**
 * Get Administrative Vocabulary
 * @param array $location
 * @return string
 */
function getAdministrativeVocabulary(array $location)
{
    $administrativeVocabulary = getLocationVocabularies($location);

    if (empty($administrativeVocabulary)) {
        return '<em>Not Available</em>';
    } else {
        return sprintf(
            '%s - %s (Code:%s , level: %s)',
            $location['vocabulary'],
            $administrativeVocabulary,
            $location['code'],
            $location['level']
        );
    }

}

/**
 * Get the location point provided an array of location in format.
 * @param array $location
 * @return string
 */
function getLocationPoint(array $location)
{
    $latitude  = $location['point'][0]['position'][0]['latitude'];
    $longitude = $location['point'][0]['position'][0]['longitude'];
    $srsLink   = sprintf(
        '<a href="%s" target="_blank">%s</a>',
        $location['point'][0]['srs_name'],
        $location['point'][0]['srs_name']
    );
    $latLong   = (empty($latitude && $longitude)) ? '<em>Not Available</em>' : sprintf('%s, %s', $latitude, $longitude);

    return sprintf('%s (<em>SRS Name: %s </em>)', $latLong, $srsLink);
}

/**
 * Returns the location properties values based upon the code is provided in a specific format.
 * @param array $location
 * @param       $codeType
 * @param       $codeNameType
 * @param int   $lengthToCut
 * @return string
 */
function getLocationPropertiesValues(array $location, $codeType, $codeNameType, $lengthToCut = -4)
{
    $codeValue         = $location[$codeType][0]['code'];
    $codeNameWithValue = getCodeNameWithCodeValue($codeNameType, $codeValue, $lengthToCut);

    return $codeNameWithValue;
}

/**
 * Get sector information when sector array is provided.
 * @param array $sector
 * @return string
 */
function getSectorInformation(array $sector)
{
    $sectorVocabulary = $sector['sector_vocabulary'];

    if ($sectorVocabulary == 1 || $sectorVocabulary == "") {
        $sectorCodeValue = app('App\Helpers\GetCodeName')->getCodeNameOnly('Sector', $sector['sector_code'], -7);

        return sprintf('%s - %s', $sector['sector_code'], $sectorCodeValue);
    } else {
        if ($sectorVocabulary === "2") {
            $sectorCodeValue = app('App\Helpers\GetCodeName')->getCodeNameOnly(
                'SectorCategory',
                $sector['sector_category_code'],
                -5
            );

            return sprintf('%s - %s', $sector['sector_category_code'], $sectorCodeValue);
        } else {
            return $sector['sector_text'];
        }
    }
}

/**
 * Returns the clickable link when the link is provided
 * @param $url
 * @return string
 */
function getClickableLink($url)
{
    return ($url == "") ? '<em>Not Available</em>' : sprintf("<a target='_blank' href='%s'> %s</a>", $url, $url);
}

/**
 * Returns the codename with Code value in format. eg. 1 - Exact
 * @param $codeNameType
 * @param $codeValue
 * @param $lengthToCut
 * @return string
 */
function getCodeNameWithCodeValue($codeNameType, $codeValue, $lengthToCut)
{
    $codeName = app('App\Helpers\GetCodeName')->getCodeNameOnly($codeNameType, $codeValue, $lengthToCut);

    if ($codeValue == "") {
        return sprintf('<em>Not Available</em>');
    } else {
        return sprintf('%s - %s', $codeValue, ucfirst($codeName));
    }
}

/**
 * Get the country budget items in format:
 * @param       $vocabularyType
 * @param array $countryBudgetItem
 * @return string
 */
function getCountryBudgetItems($vocabularyType, array $countryBudgetItem)
{
    $budgetItemCode = ($vocabularyType == 1) ? $countryBudgetItem['code'] : $countryBudgetItem['code_text'];

    return sprintf('%s (%s%s)', $budgetItemCode, $countryBudgetItem['percentage'], '%');
}

/**
 * Get Budget of the country with currency. In format: 202020 Nepalese Rupee ( Valued at May 13, 2016)
 * @param array $budget
 * @param null  $key
 * @return string
 */
function getBudgetInformation($key = null, array $budget)
{
    $budgetInformation                            = [];
    $budgetValue                                  = $budget['value'][0];
    $currencyDate                                 = getCurrencyValueDate($budgetValue);
    $period                                       = getBudgetPeriod($budget);
    $budgetInformation['currency_with_valuedate'] = $currencyDate;
    $budgetInformation['period']                  = $period;
    $budgetInformation['status']                  = getCodeNameWithCodeValue('BudgetStatus', $budget['status'], -4);

    return (array_key_exists($key, $budgetInformation) ? $budgetInformation[$key] : null);
}

/**
 * Group the budget elements and planned disbursement elements according to type.
 * @param $budgets
 * @param $type
 * @return array
 */
function groupBudgetElements($budgets, $type)
{
    $newBudgetItems = [];

    foreach ($budgets as $budget) {
        $budgetType                    = (empty($budget[$type])) ? "1" : $budget[$type];
        $newBudgetItems[$budgetType][] = $budget;
    }

    return $newBudgetItems;
}

/**
 * Get the currency with its value date and currency code value. Eg. 20000 Lek (Valued at August 13, 2016)
 * @param $budgetValue
 * @return string
 */
function getCurrencyValueDate($budgetValue)
{
    $budgetAmount = $budgetValue['amount'];
    $currency     = $budgetValue['currency'];
    $valueDate    = formatDate($budgetValue['value_date']);
    $currency     = app('App\Helpers\GetCodeName')->getCodeNameOnly('Currency', $currency, -6);

    return sprintf(
        '%s %s (<em>Valued at %s</em>)',
        $budgetAmount,
        $currency,
        $valueDate
    );

}

/**
 * Get Budget Period.
 * @param array $budget
 * @return string
 */
function getBudgetPeriod(array $budget)
{
    $periodStart = formatDate($budget['period_start'][0]['date']);
    $periodEnd   = formatDate($budget['period_end'][0]['date']);

    return sprintf('%s-%s', $periodStart, $periodEnd);

}

/**
 * Get the planned disbursement organization details.
 * @param array $disbursement
 * @param       $type
 * @return string
 */
function getDisbursementOrganizationDetails(array  $disbursement, $type)
{
    $organization = $disbursement[$type][0];
    $ref          = $organization['ref'];
    $activity_id  = $organization['activity_id'];
    $type         = $organization['type'];

    $details = sprintf(
        '(<em>Ref: %s , Activity id: %s , Type: %s</em>)',
        checkIfEmpty($ref),
        checkIfEmpty($activity_id),
        checkIfEmpty($type)
    );

    return $details;
}