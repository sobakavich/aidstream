<?php namespace App\Core\V201\Parser;

/**
 * Class ResultRow
 * @package App\Core\V201\Parser
 */
class ResultRow
{
    /**
     * @var ResultCsvFieldChecker
     */
    protected $resultCsvFieldChecker;
    /**
     * @var ResultDataParser
     */
    protected $resultDataParser;

    /**
     * @param ResultCsvFieldChecker $resultCsvFieldChecker
     * @param ResultDataParser      $resultDataParser
     */
    public function __construct(ResultCsvFieldChecker $resultCsvFieldChecker, ResultDataParser $resultDataParser)
    {
        $this->resultCsvFieldChecker = $resultCsvFieldChecker;
        $this->resultDataParser      = $resultDataParser;
    }

    /**
     * return result rows with validation messages
     * @param $row
     * @return mixed
     */
    public function getVerifiedRow($row)
    {
        $checker = $this->resultCsvFieldChecker->init($row);
        $checker->checkType();
        $checker->checkAggregationStatus();
        $checker->checkTitle();

        $errors           = $checker->getErrors();
        $result['data']   = $row;
        $result['errors'] = $errors;

        return $result;
    }

    /**
     * @param array $row
     * @param array $results
     */
    public function prepareResult(array $row, array &$results)
    {
        $parser = $this->resultDataParser->init($row, $results);
        $parser->setType()
               ->setAggregationStatus()
               ->setTitle()
               ->setDescription()
               ->setIndicator()
               ->setIndicatorTitle()
               ->setIndicatorDescription()
               ->setIndicatorReference()
               ->setIndicatorBaseline()
               ->setIndicatorBaselineComment()
               ->setIndicatorPeriod()
               ->setIndicatorTargetLocation()
               ->setIndicatorTargetDimension()
               ->setIndicatorTargetComment()
               ->setIndicatorTargetLocation('actual')
               ->setIndicatorTargetDimension('actual')
               ->setIndicatorTargetComment('actual');

        $results[] = $parser->getResultData();
    }

    /**
     * prepare result data and save
     * @param array $result
     * @return static
     */
    public function save(array $result)
    {
        $parser = $this->resultDataParser->init($result);
        $parser->setIdentifier();
        $parser->setTitle();
        $parser->setDescription();
        $parser->setStatus();
        $parser->setDate();
        $parser->setParticipatingOrg();
        $parser->setRecipientCountry();
        $parser->setRecipientRegion();
        $parser->setSector();

        return $parser->save();
    }
}
