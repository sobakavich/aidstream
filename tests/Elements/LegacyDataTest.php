<?php namespace Test\Elements;

use App\Migration\Elements\LegacyData;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\LegacyDataDataProvider;

class LegacyDataTest extends AidStreamTestCase
{
    use LegacyDataDataProvider;

    protected $legacyData;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->legacyData = new LegacyData();
    }

    /** {@test} */
    public function itShouldFormatLegacyData()
    {
        $legacyData           = $this->getTestLegacyData();
        $this->expectedOutput = $this->formatLegacyData($legacyData);

        $this->assertEquals($this->expectedOutput, $this->legacyData->format($legacyData));
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function formatLegacyData($legacyData)
    {
        $template = getHeaders('ActivityData', 'legacyData')[0];

        $template['name']            = $legacyData->name;
        $template['value']           = $legacyData->value;
        $template['iati_equivalent'] = $legacyData->iati_equivalent;

        return $template;
    }
}
