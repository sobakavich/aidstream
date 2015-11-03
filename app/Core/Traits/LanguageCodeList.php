<?php namespace App\Core\Traits;

trait LanguageCodeList
{
    use CodeList;

    /**
     * @param $codeListName
     * @param $codeListType
     * @return array
     */
    public function getLanguageCodeList($codeListName, $codeListType)
    {
        return $this->getCodeList($codeListName, $codeListType);
    }
}
