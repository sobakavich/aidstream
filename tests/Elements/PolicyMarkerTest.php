<?php namespace Test\Elements;

use App\Migration\Elements\PolicyMarker;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\PolicyMarkerDataProvider;

class PolicyMarkerTest extends AidStreamTestCase
{
    use PolicyMarkerDataProvider;

    protected $policyMarker;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->policyMarker = new PolicyMarker();
    }

    /** {@test} */
    public function itShouldFormatPolicyMarker()
    {
        $policyMarker          = $this->getTestPolicyMarker();
        $policyMarkerNarrative = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage']);

        $this->expectedOutput = $this->formatPolicyMarker($policyMarker, $policyMarkerNarrative);

        $this->assertEquals($this->expectedOutput, $this->policyMarker->format($policyMarker, $policyMarkerNarrative));
    }

    /** {@test} */
    public function itShouldFormatPolicyMarkerWithEmptyNarratives()
    {
        $policyMarker          = $this->getTestPolicyMarker();
        $policyMarkerNarrative = $this->getTestNarratives();
        $this->expectedOutput  = $this->formatPolicyMarker($policyMarker, $policyMarkerNarrative);

        $this->assertEquals($this->expectedOutput, $this->policyMarker->format($policyMarker, $policyMarkerNarrative));
    }

    /**
     * Format PolicyMarker.
     * @param $policyMarker
     * @param $policyMarkerNarrative
     * @return mixed
     */
    protected function formatPolicyMarker($policyMarker, $policyMarkerNarrative)
    {
        $template = getHeaders('ActivityData', 'policyMarker')[0];

        $template['vocabulary']    = fetchCode($policyMarker->vocabulary, 'PolicyMarkerVocabulary');
        $template['significance']  = fetchCode($policyMarker->significance, 'PolicySignificance');
        $template['policy_marker'] = fetchCode($policyMarker->code, 'PolicyMarker');
        $template['narrative']     = $policyMarkerNarrative;

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
