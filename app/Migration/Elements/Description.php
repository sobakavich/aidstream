<?php namespace App\Migration\Elements;


class Description
{
    public function format($descriptionNarratives, $typeCode)
    {
        $language      = '';
        $dataNarrative = [];

        foreach ($descriptionNarratives as $eachNarrative) {
            $narrative_text = $eachNarrative->text;

            if ($eachNarrative->xml_lang_id != "") {
                $language = getLanguageCodeFor($eachNarrative->xml_lang_id);
            }

            $dataNarrative[] = ['narrative' => $narrative_text, 'language' => $language];
        }

        return ['type' => $typeCode, 'narrative' => $dataNarrative];
    }
}
