<?php namespace Test\Elements\DataProviders;


trait ActivityDocumentLinkDataProvider
{
    use TestObjectCreator;

    protected function getTestDocumentLink()
    {
        return $this->createTestObjectWith(['id' => '1', '@url' => 'http://test.test', '@format' => '2', 'activity_id' => '1', 'url' => 'http://test.test', 'format' => '2']);
    }

    protected function getTestTitle()
    {
        return ['narrative' => $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage', 'testLanguage2'])];
    }

    protected function getTestCategory()
    {
        return [
            ['code' => 'A11']
        ];
    }

    protected function getTestLanguage()
    {
        return [['language' => 'en']];
    }

    protected function getEmptyTestTitle()
    {
        return ['narrative' => $this->getTestNarratives()];
    }
}
