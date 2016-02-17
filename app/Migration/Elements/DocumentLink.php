<?php namespace App\Migration\Elements;


class DocumentLink
{
    public function format($url, $format, $titleNarratives, $categories, $languages, $recipientCountries)
    {
        $categoryData         = $this->fetchDocumentLinkCategory($categories);
        $languageData         = $this->fetchDocumentLinkLanguage($languages);
        $recipientCountryData = $this->fetchDocumentLinkRecipientCountry($recipientCountries);
        $titleNarrativeData   = $this->fetchDocumentLinkTitleNarrative($titleNarratives);

        return [
            'url'               => $url,
            'format'            => $format,
            'narrative'         => $titleNarrativeData,
            'category'          => $categoryData,
            'language'          => $languageData,
            'recipient_country' => $recipientCountryData
        ];
    }

    /**
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

    /**
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
}
