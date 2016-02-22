<?php namespace Test\Elements\DataProviders;


trait CountryBudgetItemDataProvider
{
    use TestObjectCreator;

    protected function getTestCountryBudgetItemsData()
    {
        return [
            'code' => '1',
            'percentage' => '10',
            'description' => [
                [
                    'narrative' => $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2'])
                ]
            ]
        ];
    }

    protected function getTestCountryBudgetItemsDataWithEmptyNarratives()
    {
        return [
            'code' => '1',
            'percentage' => '10',
            'description' => [
                [
                    'narrative' => $this->getTestNarratives()
                ]
            ]
        ];
    }
}
