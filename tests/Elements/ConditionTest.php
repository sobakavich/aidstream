<?php namespace Test\Elements;

use App\Migration\Elements\Condition;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\ConditionDataProvider;

class ConditionTest extends AidStreamTestCase
{
    use ConditionDataProvider;

    protected $condition;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->condition = new Condition();
    }

    /** {@test} */
    public function itShouldFormatCondition()
    {
        $condition = $this->getTestConditionData();
        $attached  = '1';

        $this->expectedOutput = $this->formatCondition($attached, $condition);

        $this->assertEquals($this->expectedOutput, $this->condition->format($attached, $condition));
    }

    /** {@test} */
    public function itShouldFormatConditionWithEmptyNarratives()
    {
        $condition = $this->getTestConditionDataWithEmptyNarratives();
        $attached  = '1';

        $this->expectedOutput = $this->formatCondition($attached, $condition);

        $this->assertEquals($this->expectedOutput, $this->condition->format($attached, $condition));
    }

    protected function formatCondition($attached, $condition)
    {
        $template = getHeaders('ActivityData', 'conditions');

        $template['condition_attached'] = $attached;
        $template['condition']          = $condition;

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
