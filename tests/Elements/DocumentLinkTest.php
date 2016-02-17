<?php namespace Test\Elements;

use App\Migration\Elements\DocumentLink;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\DocumentLinkDataProvider;

class DocumentLinkTest extends AidStreamTestCase
{
    use DocumentLinkDataProvider;

    protected $documentLink;
    protected $expectedOutput;
    protected $documentLinkTemplate;

    public function setUp()
    {
        parent::setUp();
        $this->documentLink         = new DocumentLink();
        $this->documentLinkTemplate = getHeaders('OrganizationData', 'documentLink')[0];
    }

    /** {@test} */
    public function itShouldFormatDocumentLink()
    {
        $url                = 'testUrl';
        $format             = fetchCode('1', 'FileFormat', '');
        $titleNarratives    = $this->getTestTitleNarrative();
        $categories         = $this->getTestCategory();
        $languages          = $this->getTestLanguage();
        $recipientCountries = $this->getTestRecipientCountry();

        $this->expectedOutput = $this->formatDocumentLink($url, $format, $titleNarratives, $categories, $languages, $recipientCountries);

        $this->assertEquals(
            $this->expectedOutput,
            $this->documentLink->format(
                $url,
                $format,
                $titleNarratives,
                $categories,
                $languages,
                $recipientCountries
            )
        );
    }

    /** {@test} */
    public function itShouldFormatDocumentLinkWithMultipleValues()
    {
        $url                = 'testUrl';
        $format             = fetchCode('1', 'FileFormat', '');
        $titleNarratives    = $this->getTestTitleNarrative();
        $categories         = $this->getTestCategories();
        $languages          = $this->getTestLanguages();
        $recipientCountries = $this->getTestRecipientCountries();

        $this->expectedOutput = $this->formatDocumentLink($url, $format, $titleNarratives, $categories, $languages, $recipientCountries);

        $this->assertEquals(
            $this->expectedOutput,
            $this->documentLink->format(
                $url,
                $format,
                $titleNarratives,
                $categories,
                $languages,
                $recipientCountries
            )
        );
    }

    /**
     * Format DocumentLink.
     * @param $url
     * @param $format
     * @param $titleNarratives
     * @param $categories
     * @param $languages
     * @param $recipientCountries
     * @return array
     */
    protected function formatDocumentLink($url, $format, $titleNarratives, $categories, $languages, $recipientCountries)
    {
        $categoryData         = $this->fetchDocumentLinkCategory($categories);
        $languageData         = $this->fetchDocumentLinkLanguage($languages);
        $recipientCountryData = $this->fetchDocumentLinkRecipientCountry($recipientCountries);
        $titleNarrativeData   = $this->fetchDocumentLinkTitleNarrative($titleNarratives);

        $this->documentLinkTemplate['url']               = $url;
        $this->documentLinkTemplate['format']            = $format;
        $this->documentLinkTemplate['narrative']         = $titleNarrativeData;
        $this->documentLinkTemplate['category']          = $categoryData;
        $this->documentLinkTemplate['language']          = $languageData;
        $this->documentLinkTemplate['recipient_country'] = $recipientCountryData;

        return $this->documentLinkTemplate;
    }

    /**
     * Format DocumentLink Category.
     * @param $categories
     * @return array
     */
    protected function fetchDocumentLinkCategory($categories)
    {
        $categoryData = [];

        foreach ($categories as $category) {
            $categoryData[] = ['code' => fetchCode($category->code, 'DocumentCategory', '')];
        }

        return $categoryData;
    }

    /**
     * Format DocumentLink Language.
     * @param $languages
     * @return array
     */
    protected function fetchDocumentLinkLanguage($languages)
    {
        $languageData = [];

        foreach ($languages as $language) {
            $languageData[] = ['language' => getLanguageCodeFor($language->code)];
        }

        return $languageData;
    }

    /**
     * Format DocumentLink RecipientCountry.
     * @param $recipientCountries
     * @return array
     */
    protected function fetchDocumentLinkRecipientCountry($recipientCountries)
    {
        $recipientCountryData = [];

        foreach ($recipientCountries as $recipientCountry) {
            $recipientCountryCode       = fetchCode($recipientCountry->code, 'Country', '');
            $narratives                 = fetchNarratives($recipientCountry->id, 'iati_organisation/document_link/recipient_country/narrative', 'recipient_country_id');
            $recipientCountryNarratives = fetchAnyNarratives($narratives);
            $recipientCountryData[]     = ['code' => $recipientCountryCode, 'narrative' => $recipientCountryNarratives];
        }

        return $recipientCountryData;
    }

    /**
     * Format DocumentLink TitleNarrative.
     * @param $titleNarratives
     * @return array
     */
    protected function fetchDocumentLinkTitleNarrative($titleNarratives)
    {
        $narrativeData = [['narrative' => "", 'language' => ""]];

        if ($titleNarratives) {
            $narratives    = fetchNarratives($titleNarratives->id, 'iati_organisation/document_link/title/narrative', 'title_id');
            $narrativeData = fetchAnyNarratives($narratives);
        }

        return $narrativeData;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
