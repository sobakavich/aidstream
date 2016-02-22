<?php namespace App\Migration\Elements;


class LegacyData
{
    public function format($legacyData)
    {
        return ['name' => $legacyData->name, 'value' => $legacyData->value, 'iati_equivalent' => $legacyData->iati_equivalent];
    }
}
