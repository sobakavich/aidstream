<?php namespace App\Services\CsvImporter\Entities\Activity\Result;

use App\Services\CsvImporter\Entities\ResultCsv;

/**
 * Class Result
 * @package App\Services\CsvImporter\Entities\Activity
 */
class Result extends ResultCsv
{
    protected $rowTracker = [];

    /**
     * Result constructor.
     * @param $rows
     * @param $organizationId
     * @param $userId
     */
    public function __construct($rows, $organizationId, $userId)
    {
        $this->csvRows        = $rows;
        $this->organizationId = $organizationId;
        $this->userId         = $userId;
        $this->rows           = $rows;

        $this->rowTracker = $this->loadRowTracker();
    }

    protected function loadRowTracker()
    {
        return json_decode(file_get_contents(app_path('Services/CsvImporter/Entities/Activity/Components/Counter/CsvRowTracker.json')), true);
    }

    /**
     * Process the Result Csv.
     *
     * @return $this
     */
    public function process()
    {
        foreach ($this->rows() as $index => $row) {
            $this->rowTracker = $this->initialize($row, $this->rowTracker, $index)
                                     ->mapResultRow()
                                     ->validate()
                                     ->keep();
        }

        return $this;
    }
}
