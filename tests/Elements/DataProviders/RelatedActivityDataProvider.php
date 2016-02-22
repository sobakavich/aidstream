<?php namespace Test\Elements\DataProviders;


trait RelatedActivityDataProvider
{
    use TestObjectCreator;

    protected function getTestRelatedActivityData()
    {
        return $this->createTestObjectWith(['type' => '1', 'text' => 'this is a test']);
    }
}
