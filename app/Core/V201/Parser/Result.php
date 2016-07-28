<?php namespace App\Core\V201\Parser;

use Maatwebsite\Excel\Readers\LaravelExcelReader;

/**
 * Class Result
 * @package App\Core\V201\Parser
 */
class Result
{
    /**
     * @var int
     */
    protected $headerCount = 33;
    /**
     * @var ResultRow
     */
    protected $resultRow;

    /**
     * @param ResultRow $resultRow
     */
    public function __construct(ResultRow $resultRow)
    {
        $this->resultRow = $resultRow;
    }

    /**
     * Checks if $csvData has Result template
     * @param array $firstRow
     * @return Result|bool
     */
    public function getTemplate(array $firstRow)
    {
        if ((count($firstRow) == $this->headerCount)) {
            return $this;
        }

        return false;
    }

    /**
     * return imported result with validation messages
     * @param LaravelExcelReader $csvData
     * @return array
     */
    public function getVerifiedResults(LaravelExcelReader $csvData)
    {
        $csvData = $csvData->toArray();
        $results = $this->prepareResults($csvData);
        $results = [];
        foreach ($csvData as $row) {
            $results[] = $this->resultRow->getVerifiedRow($row);
        }

        $results['duplicate_identifiers'] = $this->getDuplicateIdentifiers($csvData);

        return $results;
    }

    /**
     * @param $csvData
     * @return array
     */
    protected function prepareResults($csvData)
    {
        $results = [];
        foreach ($csvData as $row) {
            $this->resultRow->prepareResult($row, $results);
        }

//        echo '<pre style="font-size:10px;">';
//        print_r($results);
        dd($csvData[0], $results);

        return $results;
    }

    /**
     * return duplicate identifiers
     * @param $csvData
     * @return array
     */
    protected function getDuplicateIdentifiers($csvData)
    {
        $identifierList       = [];
        $duplicateIdentifiers = [];
        foreach ($csvData as $row) {
            $identifier = $row['result_identifier'];
            !in_array($identifier, $identifierList) ?: $duplicateIdentifiers[] = $identifier;
            $identifierList[] = $identifier;
        }

        return array_unique($duplicateIdentifiers);
    }

    /**
     * save selected results
     * @param array $results
     * @return array
     */
    public function save(array $results)
    {
        $importedResults = [];
        foreach ($results as $result) {
            $importedResults[] = $this->resultRow->save(json_decode($result, true));
        }

        return $importedResults;
    }
}
