<?php namespace App\Core\V201\Repositories;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityDocumentLink;
use Illuminate\Database\DatabaseManager;

class MigrateDocumentLinks
{
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var ActivityDocumentLink
     */
    protected $activityDocumentLink;
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @param Activity             $activity
     * @param ActivityDocumentLink $activityDocumentLink
     * @param DatabaseManager      $databaseManager
     */
    public function __construct(Activity $activity, ActivityDocumentLink $activityDocumentLink, DatabaseManager $databaseManager)
    {
        $this->activity             = $activity;
        $this->activityDocumentLink = $activityDocumentLink;
        $this->databaseManager      = $databaseManager;
    }

    /**
     * migrates document link data from activity_data to activity_document_links table
     * @return bool
     */
    public function migrate()
    {
        try {
            $this->databaseManager->beginTransaction();
            $activities = $this->activity->all();
            foreach ($activities as $activity) {
                $this->migrateDocumentLinks($activity);
            }
            $this->databaseManager->commit();

            return true;
        } catch (\Exception $e) {
            $this->databaseManager->rollback();

            return false;
        }
    }

    /**
     * migrates document links of an activity
     * @param Activity $activity
     */
    protected function migrateDocumentLinks(Activity $activity)
    {
        $documentLinks = (array) $activity->document_link;
        $activityId    = $activity->id;
        foreach ($documentLinks as $documentLink) {
            $this->saveDocumentLink($documentLink, $activityId);
        }
    }

    /**
     * migrates a document link
     * @param array $documentLink
     * @param       $activityId
     */
    protected function saveDocumentLink(array $documentLink, $activityId)
    {
        $documentLinkRow['activity_id']   = $activityId;
        $documentLinkRow['document_link'] = $documentLink;
        $this->activityDocumentLink->create($documentLinkRow);
    }

    /**
     * verifies either document link got migrated successfully
     */
    public function verify()
    {
        $activities = $this->activity->all();
        foreach ($activities as $activity) {
            $activityDocumentLinksCount = count($activity->document_link);
            $documentLinksCount         = $activity->documentLinks()->count();
            $countCheck                 = $activityDocumentLinksCount == $documentLinksCount;
            dump(sprintf('%s => %s', $countCheck ? 'True' : 'False', $activity->id));
            if (!$countCheck) {
                return false;
            }
        }
    }

}
