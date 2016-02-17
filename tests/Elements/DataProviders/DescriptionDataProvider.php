<?php namespace Test\Elements\DataProviders;


trait DescriptionDataProvider
{
    use TestObjectCreator;

    protected function getTestDescriptionData()
    {
        return [$this->createTestObjectWith(['id' => '1', '@xml_lang' => '12', 'text' => 'test', 'description_id' => '1', 'xml_lang_id' => '12'])];
    }

    protected function getTestDescriptionDataWithMultipleNarratives()
    {
        return [
            $this->createTestObjectWith(['id' => '1', '@xml_lang' => '12', 'text' => 'test', 'description_id' => '1', 'xml_lang_id' => '12']),
            $this->createTestObjectWith(['id' => '2', '@xml_lang' => '12', 'text' => 'test', 'description_id' => '1', 'xml_lang_id' => '12']),
        ];
    }
}
