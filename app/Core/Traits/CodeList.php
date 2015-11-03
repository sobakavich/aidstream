<?php namespace App\Core\Traits;

trait CodeList
{

    /**
     * @param $codeListName
     * @param $codeListType
     * @return array
     */
    public function getCodeList($codeListName, $codeListType)
    {
        $codeListContent = file_get_contents(
            app_path(
                "Core/" . session()->get('version') . "/Codelist/" . config('app.locale') . "/$codeListType/$codeListName.json"
            )
        );
        $codeListData    = json_decode($codeListContent, true);
        $codeList        = $codeListData[$codeListName];
        $data            = [];

        foreach ($codeList as $list) {
            (!empty($list['name'])) ? $code = $list['code'] . ' - ' . $list['name'] : $code = $list['code'];
            $data[$list['code']] = $code;
        }

        return $data;
    }
}
