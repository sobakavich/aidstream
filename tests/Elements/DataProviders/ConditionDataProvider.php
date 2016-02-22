<?php namespace Test\Elements\DataProviders;


trait ConditionDataProvider
{
    use TestObjectCreator;

    protected function getTestConditionData()
    {
        return [
            'condition_type' => '1',
            'narrative' => $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage'])
        ];
    }

    protected function getTestConditionDataWithEmptyNarratives()
    {
        return [
            'condition_type' => '1',
            'narrative' => $this->getTestNarratives()
        ];
    }
}
