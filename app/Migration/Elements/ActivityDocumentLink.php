<?php namespace App\Migration\Elements;


class ActivityDocumentLink
{
    public function format($documentLink, $fileFormat, $title, $category, $language)
    {
        return [
            'url'      => ($documentLink->url) ? ($documentLink->url) : '',
            'format'   => $fileFormat,
            'title'    => [$title],
            'category' => $category,
            'language' => $language
        ];
    }
}
