<?php namespace Test\Elements\DataProviders;


trait CountryBudgetItemDataProvider
{
    use TestObjectCreator;

    protected function getTestCountryBudgetItemsData($budgetCode)
    {
        return ($budgetCode == '1')
            ? [
                'code'        => $budgetCode,
                'code_text'   => '',
                'percentage'  => '10',
                'description' => [
                    [
                        'narrative' => $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2'])
                    ]
                ]
            ]
            : [
                'code'        => '',
                'code_text'   => $budgetCode,
                'percentage'  => '10',
                'description' => [
                    [
                        'narrative' => $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2'])
                    ]
                ]
            ];
    }

    protected function getTestCountryBudgetItemsDataWithEmptyNarratives($budgetCode)
    {
        return ($budgetCode == '1')
            ? [
                'code'        => $budgetCode,
                'code_text'   => '',
                'percentage'  => '10',
                'description' => [
                    [
                        'narrative' => $this->getTestNarratives()
                    ]
                ]
            ]
            : [
                'code'        => '',
                'code_text'   => $budgetCode,
                'percentage'  => '10',
                'description' => [
                    [
                        'narrative' => $this->getTestNarratives()
                    ]
                ]
            ];
    }

    protected function getEmptyCountryBudgetItemArray($budgetCode)
    {
        return [
            'code'        => $budgetCode,
            'code_text'   => '',
            'percentage'  => '10',
            'description' => [
                [
                    'narrative' => $this->getTestNarratives()
                ]
            ]
        ];
    }
}
