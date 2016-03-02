<?php namespace App\Migration\Migrator\Data;

use Illuminate\Database\DatabaseManager;

class PublishToRegisterQuery extends Query
{
    public function executeFor($filenameCollection)
    {
        $this->initDBConnection();

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
        $db                   = app()->make(DatabaseManager::class)->connection('pgsql');
        $filePath             = base_path('public/files/xml') . '/' . $filename;
        $activities           = [];

        if (file_exists($filePath)) {
            $xml = simplexml_load_string(file_get_contents($filePath));
            $xml = json_decode(json_encode($xml), true);

            foreach ($xml['iati-activity'] as $each) {
                $identifier = $each['iati-identifier'];

                if ($identifier) {
                    $activity = $db->table('activity_data')->select('id')->whereRaw("identifier ->> 'iati_identifier_text' = '$identifier'")->first();

                    if ($activity) {
                        $activities[] = $activity->id;
                    }
                }
            }
        }

        return $activities;
    }
}
