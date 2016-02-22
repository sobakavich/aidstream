<?php namespace App\Migration\Elements;


class PlannedDisbursement
{
    public function format($plannedDisbursement)
    {
        $plannedDisbursementId = $plannedDisbursement->id;

        return [
            'planned_disbursement_type' => $plannedDisbursement->type,
            'period_start'              => fetchPeriodStart('iati_planned_disbursement', 'planned_disbursement_id', $plannedDisbursementId),
            'period_end'                => fetchPeriodEnd('iati_planned_disbursement', 'planned_disbursement_id', $plannedDisbursementId),
            'value'                     => fetchValue('iati_planned_disbursement', 'planned_disbursement_id', $plannedDisbursementId)
        ];
    }
}
