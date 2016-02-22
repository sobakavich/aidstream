<?php namespace Test\Elements\DataProviders;


trait LegacyDataDataProvider
{
    use TestObjectCreator;

    protected function getTestLegacyData()
    {
        return $this->createTestObjectWith(['name' => 'Test', 'value' => 'test', 'iati_equivalent' => '']);
    }
}
