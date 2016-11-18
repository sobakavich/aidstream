<?php namespace App\Services\CsvImporter\Entities;

use App\Services\CsvImporter\Entities\Activity\Components\ResultRow;

/**
 * Class Csv
 * @package App\Services\CsvImporter\Entities
 */
abstract class ResultCsv
{
    /**
     * @var
     */
    protected $rows;

    /**
     * Current Organization's id.
     *
     * @var
     */
    protected $organizationId;

    /**
     * Current User's id.
     *
     * @var
     */
    protected $userId;

    /**
     * Rows from the uploaded CSV file.
     *
     * @var array
     */
    protected $csvRows = [];

    /**
     * Initialize an ResultRow object.
     *
     * @param $row
     * @param $rowTracker
     * @param $index
     * @return ResultRow
     */
    protected function initialize($row, $rowTracker, $index)
    {
        return app()->make(ResultRow::class, [$row, $this->organizationId, $this->userId, $rowTracker, $index]);
    }

    /**
     * Get the rows in the CSV.
     *
     * @return mixed
     */
    public function rows()
    {
        return $this->rows;
    }
}
