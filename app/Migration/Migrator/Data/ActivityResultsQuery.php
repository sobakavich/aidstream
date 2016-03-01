<?php namespace App\Migration\Migrator\Data;

use App\Migration\ActivityData;
use Carbon\Carbon;

class ActivityResultsQuery extends Query
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ActivityData
     */
    protected $activityData;

    /**
     * ActivityResultsQuery constructor.
     * @param ActivityData $activityData
     * @internal param array $data
     */
    public function __construct(ActivityData $activityData)
    {
        $this->activityData = $activityData;
    }


    /**
     * @param array $accountIds
     * @return array
     */
    public function executeFor(array $accountIds)
    {
        $data = [];
        $this->initDBConnection();

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getResult($organization->id);
            }
        }

        return $data;
    }

    /**
     * get results for activities
     * @param $organizationId
     * @return array
     */
    protected function getResult($organizationId)
    {
        $resultData = [];
        $activities = $this->activityData->getActivitiesFor($organizationId);

        foreach ($activities as $activity) {
            $results = $this->connection->table('iati_result')
                                        ->select('*', '@type as type', '@aggregation_status as aggregation_status')
                                        ->where('activity_id', '=', $activity->id)
                                        ->get();

            foreach ($results as $result) {
                $resultData[$activity->id][] = $this->getDataFor($result, $activity->id);
            }
        }

        return $resultData;
    }

    /**
     * @param $result
     * @param $activityId
     * @return array
     */
    public function getDataFor($result, $activityId)
    {
        $this->data = [];
        $this->fetchResult($result, $activityId);

        return $this->data;
    }

    protected function fetchResult($result, $activityId)
    {
        $table       = 'iati_result';
        $childColumn = 'result_id';
        $resultId    = $result->id;

        $title       = $this->fetchTitle($table, $childColumn, $resultId);
        $description = $this->fetchDescription($table, $childColumn, $resultId);
        $indicator   = $this->fetchIndicator($table, $childColumn, $resultId);

        $resultData = [
            "type"               => $result->type,
            "aggregation_status" => $result->aggregation_status,
            "title"              => $title,
            "description"        => $description,
            "indicator"          => $indicator
        ];

        $this->data['activity_id'] = $activityId;
        $this->data['result']      = json_encode($resultData);
        $this->data['created_at']  = Carbon::now();
        $this->data['updated_at']  = Carbon::now();

        return $this;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     */
    protected function fetchTitle($parentTable, $column, $parentId)
    {
        $table = $parentTable . '/title';
        $title = getBuilderFor('id', $table, $column, $parentId)->first();
        if (!$title) {
            return [["narrative" => [["narrative" => "", "language" => ""]]]];
        }
        $narrative = fetchNarrative($table, 'title_id', $title->id);
        $title     = [["narrative" => $narrative]];

        return $title;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     */
    protected function fetchDescription($parentTable, $column, $parentId)
    {
        $table       = $parentTable . '/description';
        $description = getBuilderFor('id', $table, $column, $parentId)->first();
        if (!$description) {
            return [["narrative" => [["narrative" => "", "language" => ""]]]];
        }
        $narrative   = fetchNarrative($table, 'description_id', $description->id);
        $description = [["narrative" => $narrative]];

        return $description;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $totalBudgetId
     * @return array
     */
    protected function fetchIndicator($parentTable, $column, $totalBudgetId)
    {
        $table = $parentTable . '/indicator';

        $indicatorData[] = [
            "measure"     => '',
            "ascending"   => '',
            "title"       => [['narrative' => ['narrative' => '', 'language' => '']]],
            "description" => [['narrative' => ['narrative' => '', 'language' => '']]],
            "baseline"    => [['year' => '', 'value' => '', 'comment' => [['narrative' => ['narrative' => '', 'language' => '']]]]],
            "period"      => [
                [
                    'period_start' => ['date' => ''],
                    'period_end'   => ['date' => ''],
                    'target'       => [['value' => '', 'comment' => [['narrative' => ['narrative' => '', 'language' => '']]]]],
                    'actual'       => [["value" => "", "comment" => [["narrative" => [["narrative" => "", "language" => ""]]]]]]
                ]
            ]
        ];

        $indicators = getBuilderFor(['id', '@measure as measure', '@ascending as ascending'], $table, $column, $totalBudgetId)->get();

        foreach ($indicators as $indicator) {
            $indicatorData = [];
            $indicatorId   = $indicator->id;
            $childColumn   = 'indicator_id';

            $title       = $this->fetchTitle($table, $childColumn, $indicatorId);
            $description = $this->fetchDescription($table, $childColumn, $indicatorId);
            $baseline    = $this->fetchBaseline($table, $childColumn, $indicatorId);
            $period      = $this->fetchPeriod($table, $childColumn, $indicatorId);

            $indicatorData[] = [
                "measure"     => $indicator->measure,
                "ascending"   => $indicator->ascending,
                "title"       => $title,
                "description" => $description,
                "baseline"    => $baseline,
                "period"      => $period
            ];
        }

        return $indicatorData;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $totalBudgetId
     * @return array
     */
    protected function fetchBaseline($parentTable, $column, $totalBudgetId)
    {
        $table        = $parentTable . '/baseline';
        $baselineData = [];
        $baselines    = getBuilderFor(['id', '@year as year', '@value as value'], $table, $column, $totalBudgetId)->get();
        foreach ($baselines as $baseline) {
            $baselineId  = $baseline->id;
            $childColumn = 'baseline_id';

            $baselineData[] = [
                "year"    => $baseline->year,
                "value"   => $baseline->value,
                "comment" => $this->fetchComment($table, $childColumn, $baselineId),
            ];
        }

        $baselineData ?: $baselineData = [["year" => "", "value" => "", "comment" => [["narrative" => [["narrative" => "", "language" => ""]]]]]];

        return $baselineData;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $parentId
     * @return array
     */
    protected function fetchComment($parentTable, $column, $parentId)
    {
        $table   = $parentTable . '/comment';
        $comment = getBuilderFor('id', $table, $column, $parentId)->first();
        if (!$comment) {
            return [["narrative" => [["narrative" => "", "language" => ""]]]];
        }
        $narrative = fetchNarrative($table, 'comment_id', $comment->id);
        $comment   = [["narrative" => $narrative]];

        return $comment;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $totalBudgetId
     * @return array
     */
    protected function fetchPeriod($parentTable, $column, $totalBudgetId)
    {
        $table      = $parentTable . '/period';
        $periodData = [];
        $periods    = getBuilderFor('id', $table, $column, $totalBudgetId)->get();

        foreach ($periods as $period) {
            $periodId    = $period->id;
            $childColumn = 'period_id';

            $periodData[] = [
                "period_start" => fetchPeriodStart($table, $childColumn, $periodId, '/period-start'),
                "period_end"   => fetchPeriodEnd($table, $childColumn, $periodId, '/period-end'),
                "target"       => $this->fetchTarget($table, $childColumn, $periodId),
                "actual"       => $this->fetchActual($table, $childColumn, $periodId),
            ];
        }

        $periodData ?: $periodData = [
            [
                'period_start' => [['date' => '']],
                'period_end'   => [['date' => '']],
                'target'       => [["value" => "", "comment" => [["narrative" => [["narrative" => "", "language" => ""]]]]]],
                'actual'       => [["value" => "", "comment" => [["narrative" => [["narrative" => "", "language" => ""]]]]]]
            ]
        ];

        return $periodData;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $totalBudgetId
     * @return array
     */
    protected function fetchTarget($parentTable, $column, $totalBudgetId)
    {
        $table      = $parentTable . '/target';
        $targetData = [];
        $targets    = getBuilderFor(['id', '@value as value'], $table, $column, $totalBudgetId)->get();

        foreach ($targets as $target) {
            $targetId    = $target->id;
            $childColumn = 'target_id';

            $targetData[] = [
                "value"   => $target->value,
                "comment" => $this->fetchComment($table, $childColumn, $targetId),
            ];
        }

        $targetData ?: $targetData = [["value" => "", "comment" => [["narrative" => [["narrative" => "", "language" => ""]]]]]];

        return $targetData;
    }

    /**
     * @param $parentTable
     * @param $column
     * @param $totalBudgetId
     * @return array
     */
    protected function fetchActual($parentTable, $column, $totalBudgetId)
    {
        $table      = $parentTable . '/actual';
        $actualData = [];
        $actuals    = getBuilderFor(['id', '@value as value'], $table, $column, $totalBudgetId)->get();

        foreach ($actuals as $actual) {
            $actualId    = $actual->id;
            $childColumn = 'actual_id';

            $actualData[] = [
                "value"   => $actual->value,
                "comment" => $this->fetchComment($table, $childColumn, $actualId),
            ];
        }

        $actualData ?: $actualData = [["value" => "", "comment" => [["narrative" => [["narrative" => "", "language" => ""]]]]]];

        return $actualData;
    }
}
