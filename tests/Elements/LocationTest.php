<?php namespace Test\Elements;

use App\Migration\Elements\Location;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\LocationDataProvider;

class LocationTest extends AidStreamTestCase
{
    use LocationDataProvider;

    protected $location;

    protected $expectedOutput;

    protected $reference;
    protected $locationReach;
    protected $locationId;
    protected $fetchNameNarratives;
    protected $fetchDescriptionNarratives;
    protected $fetchActivityNarratives;
    protected $administrativeData;
    protected $srsName;
    protected $exactnessCode;
    protected $locationClassCode;
    protected $featureDesignationCode;
    protected $positionData;

    public function setUp()
    {
        parent::setUp();
        $this->location = new Location();
    }

    /** {@test} */
    public function itShouldFormatLocation()
    {
        $this->prepareTestData();

        $this->expectedOutput = $this->formatLocation(
            $this->reference,
            $this->locationReach,
            $this->locationId,
            $this->fetchNameNarratives,
            $this->fetchDescriptionNarratives,
            $this->fetchActivityNarratives,
            $this->administrativeData,
            $this->srsName,
            $this->exactnessCode,
            $this->locationClassCode,
            $this->featureDesignationCode,
            $this->positionData
        );

        $this->assertEquals(
            $this->expectedOutput,
            $this->location->format(
                $this->reference,
                $this->locationReach,
                $this->locationId,
                $this->fetchNameNarratives,
                $this->fetchDescriptionNarratives,
                $this->fetchActivityNarratives,
                $this->administrativeData,
                $this->srsName,
                $this->exactnessCode,
                $this->locationClassCode,
                $this->featureDesignationCode,
                $this->positionData
            )
        );
    }

    /** {@test} */
    public function itShouldFormatLocationWithEmptyFields()
    {
        $this->prepareTestDataWithEmptyFields();

        $this->expectedOutput = $this->formatLocation(
            $this->reference,
            $this->locationReach,
            $this->locationId,
            $this->fetchNameNarratives,
            $this->fetchDescriptionNarratives,
            $this->fetchActivityNarratives,
            $this->administrativeData,
            $this->srsName,
            $this->exactnessCode,
            $this->locationClassCode,
            $this->featureDesignationCode,
            $this->positionData
        );

        $this->assertEquals(
            $this->expectedOutput,
            $this->location->format(
                $this->reference,
                $this->locationReach,
                $this->locationId,
                $this->fetchNameNarratives,
                $this->fetchDescriptionNarratives,
                $this->fetchActivityNarratives,
                $this->administrativeData,
                $this->srsName,
                $this->exactnessCode,
                $this->locationClassCode,
                $this->featureDesignationCode,
                $this->positionData
            )
        );
    }

    protected function prepareTestData()
    {
        $this->reference                  = $this->getTestReference();
        $this->locationReach              = $this->getTestLocationReach();
        $this->locationId                 = $this->getTestLocationId();
        $this->fetchNameNarratives        = $this->getTestNarratives(['testNameNarrative1', 'testNameNarrative2'], ['testNameLanguage']);
        $this->fetchDescriptionNarratives = $this->getTestNarratives(['testDescriptionNarrative1', 'testDescriptionNarrative2'], ['testDescriptionLanguage']);
        $this->fetchActivityNarratives    = $this->getTestNarratives(['testActivityNarrative1', 'testActivityNarrative2'], ['testActivityLanguage1', 'testActivityLanguage2']);
        $this->administrativeData         = $this->getTestAdministrativeData();
        $this->srsName                    = $this->getTestSrsName();
        $this->exactnessCode              = $this->getTestExactnessCode();
        $this->locationClassCode          = $this->getTestLocationClassCode();
        $this->featureDesignationCode     = $this->getTestFeatureDesignationCode();
        $this->positionData               = $this->getTestPositionData();
    }

    protected function formatLocation(
        $reference,
        $locationReach,
        $locationId,
        $fetchNameNarratives,
        $fetchDescriptionNarratives,
        $fetchActivityNarratives,
        $administrativeData,
        $srsName,
        $exactnessCode,
        $locationClassCode,
        $featureDesignationCode,
        $positionData
    ) {
        $template = getHeaders('ActivityData', 'location')[0];

        $template['reference']            = isset($reference) ? $reference : "";
        $template['location_reach']       = [["code" => isset($locationReach) ? $locationReach : []]];
        $template['location_id']          = isset($locationId) ? $locationId : [['vocabulary' => "", 'code' => ""]];
        $template['name']                 = [['narrative' => isset($fetchNameNarratives) ? $fetchNameNarratives : []]];
        $template['location_description'] = [['narrative' => isset($fetchDescriptionNarratives) ? $fetchDescriptionNarratives : []]];
        $template['activity_description'] = [['narrative' => isset($fetchActivityNarratives) ? $fetchActivityNarratives : []]];
        $template['administrative']       = isset($administrativeData) ? $administrativeData : [['vocabulary' => "", 'code' => "", 'level' => ""]];
        $template['point']                = [['srs_name' => isset($srsName) ? $srsName : "", 'position' => [isset($positionData) ? $positionData : ""]]];
        $template['exactness']            = [["code" => isset($exactnessCode) ? $exactnessCode : ""]];
        $template['location_class']       = [["code" => isset($locationClassCode) ? $locationClassCode : ""]];
        $template['feature_designation']  = [["code" => isset($featureDesignationCode) ? $featureDesignationCode : ""]];

        return $template;
    }

    protected function prepareTestDataWithEmptyFields()
    {
        $this->reference                  = $this->getTestReference();
        $this->locationReach              = $this->getTestLocationReach();
        $this->locationId                 = $this->getTestLocationId();
        $this->fetchNameNarratives        = $this->getTestNarratives([]);
        $this->fetchDescriptionNarratives = $this->getTestNarratives([]);
        $this->fetchActivityNarratives    = $this->getTestNarratives([]);
        $this->administrativeData         = $this->getTestAdministrativeData();
        $this->srsName                    = $this->getTestSrsName();
        $this->exactnessCode              = $this->getTestExactnessCode();
        $this->locationClassCode          = $this->getTestLocationClassCode();
        $this->featureDesignationCode     = $this->getTestFeatureDesignationCode();
        $this->positionData               = $this->getTestPositionData();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
