<?php namespace App\Migration\Migrator\Data;

use App\Models\ActivityPublished;
use App\Models\Settings;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\File;

class PublishToRegisterQuery extends Query
{
    protected $activities = [];

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
        $db       = app()->make(DatabaseManager::class);
        $filePath = '/home/scht/homesteadSites/aidstream/public/files/xml/' . $filename;

        $xml               = $this->getFileContents($filePath);
        $activityPublished = app()->make(ActivityPublished::class);
        $activityPublished = $activityPublished->where('filename', '=', $filename)->first();
        $organizationId    = $activityPublished ? $activityPublished->organization_id : '';

        if ($xml && array_key_exists('iati-activity', $xml)) {
            foreach ($xml['iati-activity'] as $index => $each) {
                $identifier = null;

                if (is_array($each) && (array_key_exists('iati-identifier', $each))) {
                    $identifier = $each['iati-identifier'];
                } else {
                    if ($index == 'iati-identifier') {
                        $identifier = $each;
                    }
                }

                if ($identifier) {
                    $identifier = stripslashes($identifier);
                    $identifier = str_replace("'", "", $identifier);
                    $activity = $db->table('activity_data')->select('id')->whereRaw("identifier ->> 'iati_identifier_text' = '$identifier'")->first();

                    if ($activity) {
                        $this->activities[] = $activity->id;
                        $settings     = app()->make(Settings::class)->where('organization_id', '=', $organizationId)->first();

                        if ($settings) {
                            $publisherId           = $settings->registry_info[0]['publisher_id'];
                            $publishedActivityFile = sprintf('%s-%s.xml', $publisherId, $activity->id);
                            $publishedActivities   = $activityPublished->published_activities ? $activityPublished->published_activities : [];
                            $publishedActivities[] = $publishedActivityFile;

                            $activityPublished->published_activities = $publishedActivities;
                            $activityPublished->save();
                        }
                    }
                }
            }
        }

//        $file = 'activities.txt';
//        File::put($file, implode("\n", $this->activities));

        return $this->activities;
    }

    protected function getFileContents($filePath)
    {
        if (file_exists($filePath)) {
            return json_decode(json_encode(simplexml_load_string(file_get_contents($filePath))), true);
        }

        return null;
    }
}
