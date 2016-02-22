<?php namespace Test\Elements;

use App\Migration\Elements\Budget;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\BudgetDataProvider;

class BudgetTest extends AidStreamTestCase
{
    use BudgetDataProvider;

    protected $budget;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->budget = new Budget();
    }

    /** {@test} */
    public function itShouldFormatBudget()
    {
        $budget               = $this->getTestBudgetData();
        $this->expectedOutput = $this->formatBudget($budget);

        $this->assertEquals($this->expectedOutput, $this->budget->format($budget));
    }

    protected function formatBudget($budget)
    {
        $template = getHeaders('ActivityData', 'budget')[0];

        $template['budget_type']  = $budget->type;
        $template['period_start'] = fetchPeriodStart('iati_budget', 'budget_id', $budget->id);
        $template['period_end']   = fetchPeriodEnd('iati_budget', 'budget_id', $budget->id);
        $template['value']        = fetchValue('iati_budget', 'budget_id', $budget->id);

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}