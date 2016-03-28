<?php namespace App\Migration\Migrator;

use App\Helpers\ArrayToXml;
use App\Migration\Entities\PublishToRegister;
use App\Models\Activity\Activity as ActivityModel;
use App\Models\ActivityPublished;
use Illuminate\Database\DatabaseManager;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Support\Facades\File;

class PublishToRegisterMigrator implements MigratorContract
{
    /**
     * @var PublishToRegister
     */
    protected $publishToRegister;

    /**
     * @var ActivityModel
     */
    protected $activityModel;

    /**
     * PublishToRegisterMigrator constructor.
     * @param PublishToRegisterMigrator $publishToRegister
     * @param ActivityModel             $activityModel
     */
    public function __construct(PublishToRegister $publishToRegister, ActivityModel $activityModel)
    {
        $this->publishToRegister = $publishToRegister;
        $this->activityModel     = $activityModel;
    }

    /**
     * Migrate data from old system into the new one.
     * @param $accountIds
     * @return string
     */
    public function migrate(array $accountIds)
    {
        $files = [];
        $db    = app()->make(DatabaseManager::class);
        $db->beginTransaction();
        $activityPublishedInfo = $db->table('activity_published')
                                    ->select('filename')
                                    ->get();

        foreach ($activityPublishedInfo as $eachActivityPublishedInfo) {
            $files[] = $eachActivityPublishedInfo->filename;
        }

        $activities = $this->publishToRegister->getData($files);

        $file        = 'activities.txt';
        $activityIds = array_unique(explode("\n", File::get($file)));

        foreach ($activityIds as $index => $activityId) {
            $activityData = $this->activityModel->query()->where('id', '=', $activityId)->first();

            if ($activityData) {
                $activityData->published_to_registry = 1;
                if (!$activityData->save()) {
                    return 'error in updating publish_to_register';
                }
            } else {
                return "no activity updated";
            }
        }

        $db->commit();

        return "publish_to_register_field updated";
    }

    /**
     * Generate activity xml files for migrated published activities.
     * @return string
     */
    public function generateXmlFiles()
    {
        $activityPublished = app()->make(ActivityPublished::class);
        $baseFilePath      = public_path() . '/files' . config('filesystems.xml');

        foreach ($this->getAllPublishedFiles() as $file) {
            $filename          = $file->filename;
            $activityPublished = $activityPublished->where('filename', '=', $filename)->first();

            $this->generateXml($baseFilePath . $filename, $activityPublished);
        }

        return 'Old Xml files recovered';
    }

    /**
     * Get all the published files.
     * @return mixed
     */
    protected function getAllPublishedFiles()
    {
        $db = app()->make(DatabaseManager::class);

        return $db->table('activity_published')
                  ->select('filename')
                  ->get();
    }

    /**
     * Extract Activity id from the old activity xml filename.
     * @param $file
     * @return int
     */
    protected function extractActivityId($file)
    {
        return (int) substr(str_replace('.xml', '', $file), strrpos(str_replace('.xml', '', $file), '-') + 1);
    }

    /**
     * Get file contents.
     * @param $filePath
     * @return mixed|null
     */
    protected function getFileContents($filePath)
    {
        if (file_exists($filePath)) {
            return json_decode(json_encode(simplexml_load_string(file_get_contents($filePath))), true);
        }

        return null;
    }

    /**
     * Generate xml for old activities.
     * @param                   $filePath
     * @param ActivityPublished $activityPublished
     */
    protected function generateXml($filePath, ActivityPublished $activityPublished)
    {
        if (!file_exists(public_path() . '/files/sbs/')) {
            mkdir(public_path() . '/files/sbs/');
        }

        $fullXmlFile  = $this->getFileContents($filePath);

        $iatiActivity = [];

        if (is_array($fullXmlFile) && array_key_exists('iati-activity', $fullXmlFile)) {
            $iatiActivity = $fullXmlFile['iati-activity'];
        }

        $individualActivities = $activityPublished->published_activities ? $activityPublished->published_activities : [];
        $arrayToXml           = app()->make(ArrayToXml::class);

        foreach ($individualActivities as $individualActivityFilename) {
            $activityId = $this->extractActivityId($individualActivityFilename);
            $activity   = $this->activityModel->find($activityId);
            if ($activity) {
                $activityIdentifierText = $activity->identifier['iati_identifier_text'];
                $identifier             = null;

                foreach ($iatiActivity as $index => $value) {
                    if (is_int($index)) {
                        if (array_key_exists('iati-identifier', $value)) {
                            $identifier = $value['iati-identifier'];
                        }

                        if ($identifier && ($identifier == $activityIdentifierText)) {
                            $xml                  = [];
                            $xml['iati-activity'] = $value;
                            $xmlFile              = $arrayToXml->createXml('iati-activities', $xml);
                            file_put_contents(public_path() . '/files/sbs/' . $individualActivityFilename, $xmlFile->saveXml());
                        }
                    } else {
                        if (array_key_exists('iati-identifier', $iatiActivity)) {
                            $identifier = $iatiActivity['iati-identifier'];
                            if ($identifier && ($identifier == $activityIdentifierText)) {
                                $xml                  = [];
                                $xml['iati-activity'] = $iatiActivity;
                                $xmlFile              = $arrayToXml->createXml('iati-activities', $xml);
                                file_put_contents(public_path() . '/files/sbs/' . $individualActivityFilename, $xmlFile->saveXml());
                            }
                        }
                    }
                }
            }
        }
    }
}
