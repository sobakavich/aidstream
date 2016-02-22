<?php namespace Test\Elements\DataProviders;


trait PlannedDisbursementDataProvider
{
    use TestObjectCreator;

    protected function getTestPlannedDisbursement()
    {
        return $this->createTestObjectWith(['id' => '1', 'type' => 'test']);
    }

    protected function getTestPlannedDisbursementWithoutType()
    {
        return $this->createTestObjectWith(['id' => '1', 'type' => null]);
    }
}
