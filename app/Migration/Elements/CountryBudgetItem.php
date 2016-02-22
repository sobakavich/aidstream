<?php namespace App\Migration\Elements;


class CountryBudgetItem
{
    public function format($vocabularyCode, $budgetItemsArray)
    {
        return [
            'vocabulary'  => isset($vocabularyCode) ? $vocabularyCode : "",
            'budget_item' => isset($budgetItemsArray) ? $budgetItemsArray : ""
        ];
    }
}
