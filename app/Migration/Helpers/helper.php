<?php

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;

/**
 * Get Organization for an Account.
 * @param $accountId
 * @return mixed
 */
function getOrganizationFor($accountId)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table('iati_organisation')
                ->select('id')
                ->where('account_id', '=', $accountId)
                ->first();
}

/**
 * Get the Language code for a Language with the given id.
 * @param $languageId
 * @return string
 */
function getLanguageCodeFor($languageId)
{
    return ($language = app()->make(DatabaseManager::class)
                             ->connection('mysql')
                             ->table('Language')
                             ->select('Code')
                             ->where('id', '=', $languageId)
                             ->first()) ? $language->Code : '';
}

/**
 * Get an Activity Identifier object.
 * @param $activityId
 * @return mixed
 */
function getActivityIdentifier($activityId)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table('iati_identifier')
                ->select('activity_identifier', 'text')
                ->where('activity_id', '=', $activityId)
                ->first();
}

/**
 * Fetch Narratives from a given table.
 * @param $value
 * @param $table
 * @param $column
 * @return mixed
 */
function fetchNarratives($value, $table, $column)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select('*', '@xml_lang as xml_lang')
                ->where($column, '=', $value)
                ->get();
}

/**
 * Fetch any given field from any given table on the conditions specified.
 * @param $field
 * @param $table
 * @param $column
 * @param $value
 * @return Builder
 */
function getBuilderFor($field, $table, $column, $value)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select($field)
                ->where($column, '=', $value);
}

/**
 * Fetch code from a given table.
 * @param $id
 * @param $table
 * @param $act
 * @return string
 */
function fetchCode($id, $table, $act = null)
{
    return ($code = app()->make(DatabaseManager::class)
                         ->connection('mysql')
                         ->table($table)
                         ->select('Code')
                         ->where('id', '=', $id)
                         ->first()) ? $code->Code : '';
}

/**
 * @param $anyNarratives
 * @return array
 */
function fetchAnyNarratives($anyNarratives)
{
    $language  = "";
    $Narrative = [];
    foreach ($anyNarratives as $eachNarrative) {
        $narrativeText = $eachNarrative->text;
        if ($eachNarrative->xml_lang != "") {
            $language = getLanguageCodeFor($eachNarrative->xml_lang);
        }
        $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
    }
    // format incase of no narrative
    if (empty($anyNarratives)) {
        $narrative = [['narrative' => "", 'language' => ""]];
    } else {
        $narrative = $Narrative;
    }

    return $narrative;
}

/**
 * fetch data from a table
 * @param $table
 * @param $toCheckId
 * @param $id
 * @return mixed
 */
function fetchDataFrom($table, $toCheckId, $id)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select('*')
                ->where($toCheckId, '=', $id)
                ->get();
}

/**
 * fetch data from a table with code
 * @param $table
 * @param $toCheckId
 * @param $id
 * @return mixed
 */
function fetchDataWithCodeFrom($table, $toCheckId, $id)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table($table)
                ->select('*', '@code as code')
                ->where($toCheckId, '=', $id)
                ->get();

}

/**
 * @param $parentTable
 * @param $column
 * @param $parentId
 * @return array
 */
function fetchPeriodStart($parentTable, $column, $parentId)
{
    $periodStart = getBuilderFor('@iso_date as date', $parentTable . '/period_start', $column, $parentId)->first();
    $periodStart = [["date" => $periodStart->date]];

    return $periodStart;
}

/**
 * @param $parentTable
 * @param $column
 * @param $parentId
 * @return array
 */
function fetchPeriodEnd($parentTable, $column, $parentId)
{
    $periodEnd = getBuilderFor('@iso_date as date', $parentTable . '/period_end', $column, $parentId)->first();
    $periodEnd = [["date" => $periodEnd->date]];

    return $periodEnd;
}

/**
 * @param $parentTable
 * @param $column
 * @param $totalBudgetId
 * @return array
 */
function fetchBudgetLine($parentTable, $column, $totalBudgetId)
{
    $table          = $parentTable . '/budget_line';
    $budgetLineData = [];
    $budgetLines    = getBuilderFor(['id', '@ref as ref'], $table, $column, $totalBudgetId)->get();
    foreach ($budgetLines as $budgetLine) {
        $budgetLineId     = $budgetLine->id;
        $value            = fetchValue($table, 'budget_line_id', $budgetLineId);
        $narrative        = fetchNarrative($table, 'budget_line_id', $budgetLineId);
        $budgetLineData[] = [
            "reference" => $budgetLine->ref,
            "value"     => $value,
            "narrative" => $narrative
        ];
    }

    return $budgetLineData;
}

/**
 * @param $parentTable
 * @param $column
 * @param $parentId
 * @return array
 */
function fetchValue($parentTable, $column, $parentId)
{
    $fields   = ['@currency as currency', '@value_date as value_date', 'text'];
    $value    = getBuilderFor($fields, $parentTable . '/value', $column, $parentId)->first();
    $currency = fetchCode($value->currency, 'Currency');
    $value    = [["amount" => $value->text, "currency" => $currency, "value_date" => $value->value_date]];

    return $value;
}

/**
 * @param      $parentTable
 * @param      $column
 * @param      $parentId
 * @param null $customTable
 * @return array
 */
function fetchNarrative($parentTable, $column, $parentId, $customTable = null)
{
    $narratives    = getBuilderFor(['text', '@xml_lang as xml_lang'], $parentTable . ($customTable ? $customTable : '/narrative'), $column, $parentId)->get();
    $narrativeData = [];
    foreach ($narratives as $narrative) {
        $language        = getLanguageCodeFor($narrative->xml_lang);
        $narrativeData[] = ['narrative' => $narrative->text, 'language' => $language];
    }
    $narrativeData ?: $narrativeData = [['narrative' => "", 'language' => ""]];

    return $narrativeData;
}

function getActivitiesFor($accountId)
{
    $builder = app()->make(DatabaseManager::class)
                    ->connection('mysql');

    return ($activity = $builder->table('iati_activities')
                                ->select('id')
                                ->where('account_id', '=', $accountId)
                                ->first())
        ? $builder->table('iati_activity')
                  ->select('id')
                  ->where('activities_id', '=', $activity->id)
                  ->get()
        : null;
}
