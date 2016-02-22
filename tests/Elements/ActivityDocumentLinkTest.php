<?php namespace Test\Elements;

use App\Migration\Elements\ActivityDocumentLink;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\ActivityDocumentLinkDataProvider;

class ActivityDocumentLinkTest extends AidStreamTestCase
{
    use ActivityDocumentLinkDataProvider;

    protected $documentLink;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->documentLink = new ActivityDocumentLink();
    }

    /** {@test} */
    public function itShouldFormatDocumentLink()
    {
        $documentLink = $this->getTestDocumentLink();
        $title        = $this->getTestTitle();
        $format       = 'testFormat';
        $language     = $this->getTestLanguage();
        $category     = $this->getTestCategory();

        $this->expectedOutput = $this->formatDocumentLink($documentLink, $format, $title, $category, $language);
        $this->assertEquals($this->expectedOutput, $this->documentLink->format($documentLink, $format, $title, $category, $language));
    }
    
    /** {@test} */
    public function itShouldFormatDocumentLinkWithNoTitle()
    {
        $documentLink = $this->getTestDocumentLink();
        $title        = $this->getEmptyTestTitle();
        $format       = 'testFormat';
        $language     = $this->getTestLanguage();
        $category     = $this->getTestCategory();

        $this->expectedOutput = $this->formatDocumentLink($documentLink, $format, $title, $category, $language);
        $this->assertEquals($this->expectedOutput, $this->documentLink->format($documentLink, $format, $title, $category, $language));
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function formatDocumentLink($documentLink, $format, $title, $category, $language)
    {
        return [
            'url'      => ($documentLink->url) ? ($documentLink->url) : '',
            'format'   => $format,
            'title'    => [$title],
            'category' => $category,
            'language' => $language
        ];
    }
}
