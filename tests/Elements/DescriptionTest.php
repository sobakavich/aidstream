<?php namespace Test\Elements;

use App\Migration\Elements\Description;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\DescriptionDataProvider;

class DescriptionTest extends AidStreamTestCase
{
    use DescriptionDataProvider;

    protected $testInput;
    protected $expectedOutput = [];
    protected $description;

    public function setUp()
    {
        parent::setUp();
        $this->description = new Description();
    }

    /** {@test} */
    public function itShouldFormatDescription()
    {
        $typeCode              = $this->createTestObjectWith(['Code' => '1'])->Code;
        $descriptionNarratives = $this->getTestDescriptionData();
        $this->expectedOutput  = $this->formatDescription($descriptionNarratives, $typeCode);

        $this->assertEquals($this->expectedOutput, $this->description->format($descriptionNarratives, $typeCode));
    }

    /** {@test} */
    public function itShouldFormatDescriptionWithMultipleNarratives()
    {
        $typeCode              = $this->createTestObjectWith(['Code' => '1'])->Code;
        $descriptionNarratives = $this->getTestDescriptionDataWithMultipleNarratives();
        $this->expectedOutput  = $this->formatDescription($descriptionNarratives, $typeCode);

        $this->assertEquals($this->expectedOutput, $this->description->format($descriptionNarratives, $typeCode));
    }

    /**
     * Format Description according to the given template.
     * @param $descriptionNarratives
     * @param $typeCode
     * @return mixed
     */
    protected function formatDescription($descriptionNarratives, $typeCode)
    {
        $template = getHeaders('ActivityData', 'description')[0];
        $language = '';
        $output   = [];

        foreach ($descriptionNarratives as $descriptionNarrative) {
            $narrative_text = $descriptionNarrative->text;

            if ($descriptionNarrative->xml_lang_id != "") {
                $language = getLanguageCodeFor($descriptionNarrative->xml_lang_id);
            }

            $output[] = ['narrative' => $narrative_text, 'language' => $language];
        }

        $template['type']      = $typeCode;
        $template['narrative'] = $output;

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
