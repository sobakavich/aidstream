<?php namespace App\Migration\Migrator\Data;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\File;

class PublishToRegisterQuery extends Query
{
    public function executeFor($filenameCollection)
    {
//        $this->initDBConnection();

        $data = [];

        foreach ($filenameCollection as $filename) {
            $data[] = $this->getData($filename);
        }

        return $data;
    }

    /**
     * @param $filename
     * @return array
     */

    public function getData($filename)
    {
        $db         = app()->make(DatabaseManager::class)->connection('pgsql');
        $filePath   = '/home/yipl/app/staging/shared/files/xml/' . $filename;
        $activities = [];
        $file       = 'activities.txt';

        if (file_exists($filePath)) {
            $xml = simplexml_load_string(file_get_contents($filePath));
            $xml = json_decode(json_encode($xml), true);

            if ($xml && array_key_exists('iati-activity', $xml)) {
                foreach ($xml['iati-activity'] as $each) {
                    $identifier = null;
                    if (is_array($each) && array_key_exists('iati-identifier', $each)) {
                        $identifier = $each['iati-identifier'];
                    }


                    if ($identifier) {
                        $activity = $db->table('activity_data')->select('id')->whereRaw("identifier ->> 'iati_identifier_text' = '$identifier'")->first();

                        if ($activity) {
                            $activities[] = $activity->id;
                        }
                    }
                }
            }

        }
        File::put($file, implode("\n", $activities));

        return $activities;
    }
}
