<?php namespace Test\Elements;

use App\Migration\Elements\RelatedActivity;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\RelatedActivityDataProvider;

class RelatedActivityTest extends AidStreamTestCase
{
    use RelatedActivityDataProvider;

    protected $relatedActivity;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->relatedActivity = new RelatedActivity();
    }

    /** {@test} */
    public function itShouldFormatRelatedActivity()
    {
        $relatedActivity      = $this->getTestRelatedActivityData();
        $this->expectedOutput = $this->formatRelatedActivity($relatedActivity);

        $this->assertEquals($this->expectedOutput, $this->relatedActivity->format($relatedActivity));
    }

    protected function formatRelatedActivity($relatedActivity)
    {
        $template                        = getHeaders('ActivityData', 'relatedActivity')[0];
        $template['relationship_type']   = fetchCode($relatedActivity->type, 'RelatedActivityType');
        $template['activity_identifier'] = $relatedActivity->text;

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}