<?php namespace Test\Elements;

use App\Migration\Elements\CountryBudgetItem;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\CountryBudgetItemDataProvider;

class CountryBudgetItemTest extends AidStreamTestCase
{
    use CountryBudgetItemDataProvider;

    protected $countryBudgetItem;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->countryBudgetItem = new CountryBudgetItem();
    }

    /** {@test} */
    public function itShouldFormatCountryBudgetItem()
    {
        $vocabularyCode       = '1';
        $budgetItemsArray     = $this->getTestCountryBudgetItemsData();
        $this->expectedOutput = $this->formatCountryBudgetItem($vocabularyCode, $budgetItemsArray);

        $this->assertEquals($this->expectedOutput, $this->countryBudgetItem->format($vocabularyCode, $budgetItemsArray));
    }
    
    /** {@test} */
    public function itShouldFormatCountryBudgetItemWithEmptyNarratives()
    {
        $vocabularyCode       = '1';
        $budgetItemsArray     = $this->getTestCountryBudgetItemsDataWithEmptyNarratives();
        $this->expectedOutput = $this->formatCountryBudgetItem($vocabularyCode, $budgetItemsArray);

        $this->assertEquals($this->expectedOutput, $this->countryBudgetItem->format($vocabularyCode, $budgetItemsArray));
    }

    protected function formatCountryBudgetItem($vocabularyCode, $budgetItemsArray)
    {
        $template = getHeaders('ActivityData', 'countryBudgetItems')[0];

        $template['vocabulary']  = $vocabularyCode;
        $template['budget_item'] = $budgetItemsArray;

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
