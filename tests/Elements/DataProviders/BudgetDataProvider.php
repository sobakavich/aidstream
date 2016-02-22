<?php namespace Test\Elements\DataProviders;


trait BudgetDataProvider
{
    use TestObjectCreator;

    protected function getTestBudgetData()
    {
        return $this->createTestObjectWith(['id' => '1', 'type' => '23']);
    }
}
