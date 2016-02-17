<?php namespace App\Migration\Elements;


class Location
{
    public function format(
        $ref,
        $locationReach,
        $locationID,
        $fetchNameNarratives,
        $fetchDescriptionNarratives,
        $fetchActivityNarratives,
        $administrativeData,
        $srsName,
        $exactnessCode,
        $locationClassCode,
        $featureDesignationCode,
        $positionData
    ) {
        return [
            'reference'            => isset($ref) ? $ref : "",
            'location_reach'       => [["code" => isset($locationReach) ? $locationReach : []]],
            'location_id'          => isset($locationID) ? $locationID : [['vocabulary' => "", 'code' => ""]],
            'name'                 => [['narrative' => isset($fetchNameNarratives) ? $fetchNameNarratives : []]],
            'location_description' => [['narrative' => isset($fetchDescriptionNarratives) ? $fetchDescriptionNarratives : []]],
            'activity_description' => [['narrative' => isset($fetchActivityNarratives) ? $fetchActivityNarratives : []]],
            'administrative'       => isset($administrativeData) ? $administrativeData : ['vocabulary' => "", 'code' => "", 'level' => ""],
            'point'                => [['srs_name' => isset($srsName) ? $srsName : "", 'position' => [isset($positionData) ? $positionData : ""]]],
            'exactness'            => [["code" => isset($exactnessCode) ? $exactnessCode : ""]],
            'location_class'       => [["code" => isset($locationClassCode) ? $locationClassCode : ""]],
            'feature_designation'  => [["code" => isset($featureDesignationCode) ? $featureDesignationCode : ""]],
        ];
    }
}
