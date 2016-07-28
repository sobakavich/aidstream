<?php namespace App\Core\V201\Parser;

use App\Models\Activity\ActivityResult;
use App\Models\Organization\Organization;
use App\Models\Settings;

/**
 * Class ResultDataParser
 * @package App\Core\V201\Parser
 */
class ResultDataParser
{
    /**
     * @var array
     */
    protected $resultData;
    /**
     * @var array
     */
    protected $result;
    /**
     * @var ActivityResult
     */
    protected $resultModel;
    /**
     * @var
     */
    protected $indicator;
    /**
     * @var array
     */
    protected $period;

    /**
     * ResultDataParser constructor.
     * @param ActivityResult $resultModel
     */
    public function __construct(ActivityResult $resultModel)
    {
        $this->resultModel = $resultModel;
    }

    /**
     * initializes with result data
     * @param array $result
     * @param array $results
     * @return ResultDataParser
     */
    public function init(array $result, array &$results)
    {
        $this->result     = $result;
        $resultData       = $this->isResult() ? [] : array_pop($results);
        $this->resultData = $resultData;

        return $this;
    }

    /**
     * @return bool
     */
    protected function isResult()
    {
        $result = $this->result;

        return (getVal($result, ['type']) || getVal($result, ['aggregation_status']));
    }

    /**
     * @return bool
     */
    protected function isIndicator()
    {
        $result = $this->result;

        return (getVal($result, ['measure']) || getVal($result, ['ascending']) != '');
    }

    /**
     * @return bool
     */
    protected function isPeriod()
    {
        return (getVal($this->result, ['period_start']) || getVal($this->result, ['period_end']) || getVal($this->result, ['target_value']) || getVal($this->result, ['actual_value']));
    }

    /**
     * @return array
     */
    public function getResultData()
    {
        $this->indicator['period'][]     = $this->period;
        $this->resultData['indicator'][] = $this->indicator;

        return $this->resultData;
    }

    /**
     * create result
     */
    public function save()
    {
        return $this->resultModel->create($this->resultData);
    }

    /**
     * set result type
     */
    public function setType()
    {
        if ($type = getVal($this->result, ['type'])) {
            $this->resultData['type'] = $type;
        }

        return $this;
    }

    /**
     * set result aggregation status
     */
    public function setAggregationStatus()
    {
        if ($aggregationStatus = getVal($this->result, ['aggregation_status'])) {
            $this->resultData['aggregation_status'] = $aggregationStatus;
        }

        return $this;
    }

    /**
     * set result title
     */
    public function setTitle()
    {
        if ($title = getVal($this->result, ['title'])) {
            $this->resultData['title'][0]['narrative'][] = $this->getNarrative($title, $this->result['title_language']);
        }

        return $this;
    }

    /**
     * set result description
     */
    public function setDescription()
    {
        if ($description = getVal($this->result, ['description'])) {
            $this->resultData['description'][0]['narrative'][] = $this->getNarrative($description, $this->result['description_language']);
        }

        return $this;
    }

    /**
     * @param $narrative
     * @param $language
     * @return array
     */
    protected function getNarrative($narrative, $language)
    {
        return ["narrative" => $narrative, "language" => $language];
    }

    /**
     * set result indicator
     */
    public function setIndicator()
    {
        $this->indicator = ($this->isIndicator() || count($this->resultData['indicator']) == 0) ? $this->getIndicator() : array_pop($this->resultData['indicator']);

        return $this;
    }

    /**
     * @return array
     */
    protected function getIndicator()
    {
        $indicator              = [];
        $indicator['measure']   = $this->result['measure'];
        $indicator['ascending'] = $this->result['ascending'];

        return $indicator;
    }

    /**
     * @return $this
     */
    public function setIndicatorTitle()
    {
        if (($title = getVal($this->result, ['indicator_title'])) || $this->isIndicator()) {
            $this->indicator['title'][0]['narrative'][] = $this->getNarrative($title, $this->result['indicator_title_language']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setIndicatorDescription()
    {
        if (($description = getVal($this->result, ['indicator_description'])) || $this->isIndicator()) {
            $this->indicator['description'][0]['narrative'][] = $this->getNarrative($description, $this->result['indicator_description_language']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setIndicatorReference()
    {
        $reference = $this->getReference();
        if ($this->removeEmptyValues($reference) || $this->isIndicator()) {
            $this->indicator['reference'][] = $reference;
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getReference()
    {
        $reference                  = [];
        $reference['vocabulary']    = $this->result['reference_vocabulary'];
        $reference['code']          = $this->result['reference_code'];
        $reference['indicator_uri'] = $this->result['reference_uri'];

        return $reference;
    }

    /**
     * @return $this
     */
    public function setIndicatorBaseline()
    {
        if ($this->isIndicator()) {
            $this->indicator['baseline'][0] = $this->getBaseline();
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getBaseline()
    {
        $baseline          = [];
        $baseline['year']  = $this->result['baseline_year'];
        $baseline['value'] = $this->result['baseline_value'];

        return $baseline;
    }

    /**
     * @return $this
     */
    public function setIndicatorBaselineComment()
    {
        if (($comment = getVal($this->result, ['baseline_comment'])) || $this->isIndicator()) {
            $this->indicator['baseline'][0]['comment'][0]['narrative'][] = $this->getNarrative($comment, $this->result['baseline_comment_language']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setIndicatorPeriod()
    {
        $this->period = ($this->isPeriod() || count($this->indicator['period']) == 0) ? $this->getIndicatorPeriod() : array_pop($this->indicator['period']);

        return $this;
    }

    /**
     * @return array
     */
    protected function getIndicatorPeriod()
    {
        $period                 = [];
        $period['period_start'] = [$this->getDate($this->result['period_start'])];
        $period['period_end']   = [$this->getDate($this->result['period_end'])];
        $period['target']       = [$this->getTarget()];
        $period['actual']       = [$this->getTarget('actual')];

        return $period;
    }

    /**
     * @param $date
     * @return array
     */
    protected function getDate($date)
    {
        return ['date' => $date];
    }

    /**
     * @param string $type
     * @return array
     */
    protected function getTarget($type = 'target')
    {
        $target          = [];
        $target['value'] = $this->result[sprintf('%s_value', $type)];

        return $target;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setIndicatorTargetLocation($type = 'target')
    {
        if (($ref = getVal($this->result, [sprintf('%s_location_ref', $type)])) || $this->isPeriod()) {
            $this->period[$type][0]['location'][] = ['ref' => $ref];
        }

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setIndicatorTargetDimension($type = 'target')
    {
        $name  = getVal($this->result, [sprintf('%s_dimension_name', $type)]);
        $value = getVal($this->result, [sprintf('%s_dimension_value', $type)]);
        if ($name || $value) {
            $this->period[$type][0]['dimension'][] = ['name' => $name, 'value' => $value];
        }

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setIndicatorTargetComment($type = 'target')
    {
        if (($comment = getVal($this->result, [sprintf('%s_comment', $type)])) || $this->isPeriod()) {
            $this->period[$type][0]['comment'][0]['narrative'][] = $this->getNarrative($comment, $this->result[sprintf('%s_comment_language', $type)]);
        }

        return $this;
    }

    /**
     * @param $data
     * @return array
     */
    protected function removeEmptyValues($data)
    {
        foreach ($data as &$subData) {
            if (is_array($subData)) {
                $this->removeEmptyValues($subData);
            }
        }
        $data = array_filter(
            $data,
            function ($value) {
                return ($value != '' && $value != null && $value != []);
            }
        );

        return $data;
    }
}
