<?php namespace App\Migration\Migrator;

use App\Migration\Migrator\Contract\MigratorContract;
use App\Models\Activity\Activity;
use App\Services\Activity\ActivityManager;

class ActivityInRegistryMigrator implements MigratorContract
{
    /**
     * Migrate data from old system into the new one.
     * @param $accountIds
     * @return string
     */
    public function migrate(array $accountIds)
    {
        $activity        = app()->make(Activity::class);
        $activities      = $activity->where('published_to_registry', '=', 1)->get();
        $activityManager = app()->make(ActivityManager::class);

        foreach ($activities as $activity) {
            $activityManager->activityInRegistry($activity);
        }

        return 'Activity in Registry migrated.';
    }
}