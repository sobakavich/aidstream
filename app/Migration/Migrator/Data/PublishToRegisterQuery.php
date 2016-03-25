<?php namespace App\Migration\Migrator\Data;

use App\Models\ActivityPublished;
use App\Models\Settings;
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
        $db       = app()->make(DatabaseManager::class);
        $filePath = '/home/yipl/app/staging/shared/files/xml/' . $filename;
        $fileUrl  = 'http://newstage.aidstream.org/files/xml/' . $filename;
        $files = [];

        $xml               = $this->getFileContents($filePath);
        $activityPublished = app()->make(ActivityPublished::class);
        $activityPublished = $activityPublished->where('filename', '=', $filename)->first();
        $organizationId    = $activityPublished->organization_id;

        if ($xml && array_key_exists('iati-activity', $xml)) {
            foreach ($xml['iati-activity'] as $each) {
                $identifier = null;
                if (is_array($each) && array_key_exists('iati-identifier', $each)) {
                    $identifier = $each['iati-identifier'];
                }

                if ($identifier) {
                    $activity = $db->table('activity_data')->select('id')->whereRaw("identifier ->> 'iati_identifier_text' = '$identifier'")->first();

                    if ($activity) {
//                        $activities[] = $activity->id;
                        $settings     = app()->make(Settings::class)->where('organization_id', '=', $organizationId)->first();

                        if ($settings) {
                            $publisherId = $settings->registry_info[0]['publisher_id'];
                            $publishedActivityFile = sprintf('%s-%s.xml', $publisherId, $activity->id);
                            $publishedActivities = $activityPublished->published_activities ? $activityPublished->published_activities : [];
                            $publishedActivities[] = $publishedActivityFile;
                            $files[$filename][] = $publishedActivityFile;

//                            $activityPublished->update(['published_activites' => $publishedActivities]);
//                            $activityPublished->save();
                        }

                    }
                }
            }
        }

        File::put('publishedActivities.txt', json_encode($files));

//        $activities = [];
//        $file       = 'activities.txt';
//
//        if (file_exists($filePath)) {
//            $xml = simplexml_load_string(file_get_contents($filePath));
//            $xml = json_decode(json_encode($xml), true);
//
//            if ($xml && array_key_exists('iati-activity', $xml)) {
//                foreach ($xml['iati-activity'] as $each) {
//                    $identifier = null;
//                    if (is_array($each) && array_key_exists('iati-identifier', $each)) {
//                        $identifier = $each['iati-identifier'];
//                    }
//
//
//                    if ($identifier) {
//                        $activity = $db->table('activity_data')->select('id')->whereRaw("identifier ->> 'iati_identifier_text' = '$identifier'")->first();
//
//                        if ($activity) {
//                            $activities[] = $activity->id;
//                        }
//                    }
//                }
//            }
//
//        }
//        File::put($file, implode("\n", $activities));
//
//        return $activities;
    }

    protected function getFileContents($fileUrl)
    {
        return json_decode(json_encode(simplexml_load_string(file_get_contents($fileUrl))), true);
    }
}
