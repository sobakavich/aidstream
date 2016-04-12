<?php namespace App\Migration\Elements;

class Document
{
    public function format(array $docMetaData)
    {
        foreach ($docMetaData as $url => $data) {
            $docMetaData[$url]['activities'] = json_encode($data['activities']);
        }

        return $docMetaData;
    }
}