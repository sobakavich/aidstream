<?php namespace App\Migration\Elements;


class Budget
{
    public function format($budget)
    {
        return [
            'budget_type'  => $budget->type,
            'period_start' => fetchPeriodStart('iati_budget', 'budget_id', $budget->id),
            'period_end'   => fetchPeriodEnd('iati_budget', 'budget_id', $budget->id),
            'value'        => fetchValue('iati_budget', 'budget_id', $budget->id)
        ];
    }
}
