<?php namespace Test\Elements\DataProviders;


trait DocumentLinkDataProvider
{
    use TestObjectCreator;

    protected function getTestTitleNarrative()
    {
        return $this->createTestObjectWith(['id' => '11']);
    }

    protected function getTestCategory()
    {
        return [$this->createTestObjectWith(['id' => '12', '@code' => '33', '@xml_lang' => '45', 'text' => 'This is a test', 'document_link_id' => '31', 'code' => '33'])];
    }

    protected function getTestLanguage()
    {
        return [$this->createTestObjectWith(['id' => '11', '@code' => '123', 'document_link_id' => '1', 'code' => '123'])];
    }

    protected function getTestRecipientCountry()
    {
        return [$this->createTestObjectWith(['id' => '1', '@code' => '123', 'text' => 'This is a test', 'document_link_id' => '2', 'code' => '123'])];
    }

    protected function getTestCategories()
    {
        return [
            $this->createTestObjectWith(['id' => '12', '@code' => '33', '@xml_lang' => '45', 'text' => 'This is a test', 'document_link_id' => '31', 'code' => '33']),
            $this->createTestObjectWith(['id' => '21', '@code' => '35', '@xml_lang' => '54', 'text' => 'This is another test', 'document_link_id' => '31', 'code' => '35'])
        ];

    }

    protected function getTestLanguages()
    {
        return [
            $this->createTestObjectWith(['id' => '11', '@code' => '123', 'document_link_id' => '1', 'code' => '123']),
            $this->createTestObjectWith(['id' => '22', '@code' => '321', 'document_link_id' => '1', 'code' => '321'])
        ];
    }

    protected function getTestRecipientCountries()
    {
        return [
            $this->createTestObjectWith(['id' => '1', '@code' => '123', 'text' => 'This is a test', 'document_link_id' => '2', 'code' => '123']),
            $this->createTestObjectWith(['id' => '1', '@code' => '32', 'text' => 'This is another test', 'document_link_id' => '2', 'code' => '32']),
        ];
    }
}