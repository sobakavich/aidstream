<?php namespace Test\Elements;

use App\Migration\Elements\PlannedDisbursement;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\PlannedDisbursementDataProvider;

class PlannedDisbursementTest extends AidStreamTestCase
{
    use PlannedDisbursementDataProvider;

    protected $plannedDisbursement;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->plannedDisbursement = new PlannedDisbursement();
    }

    /** {@test} */
    public function itShouldFormatPlannedDisbursement()
    {
        $plannedDisbursement  = $this->getTestPlannedDisbursement();
        $this->expectedOutput = $this->formatPlannedDisbursement($plannedDisbursement);

        $this->assertEquals($this->expectedOutput, $this->plannedDisbursement->format($plannedDisbursement));

    }

    /** {@test} */
    public function itShouldFormatPlannedDisbursementWithoutType()
    {
        $plannedDisbursement  = $this->getTestPlannedDisbursementWithoutType();
        $this->expectedOutput = $this->formatPlannedDisbursement($plannedDisbursement);

        $this->assertEquals($this->expectedOutput, $this->plannedDisbursement->format($plannedDisbursement));
    }

    protected function formatPlannedDisbursement($plannedDisbursement)
    {
        $plannedDisbursementId                 = $plannedDisbursement->id;
        $template                              = getHeaders('ActivityData', 'plannedDisbursement')[0];
        $template['planned_disbursement_type'] = $plannedDisbursement->type;
        $template['period_start']              = fetchPeriodStart('iati_planned_disbursement', 'planned_disbursement_id', $plannedDisbursementId);
        $template['period_end']                = fetchPeriodEnd('iati_planned_disbursement', 'planned_disbursement_id', $plannedDisbursementId);
        $template['value']                     = fetchValue('iati_planned_disbursement', 'planned_disbursement_id', $plannedDisbursementId);

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
