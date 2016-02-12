<?php namespace App\Migration\Entities;


use App\Migration\MigrateSettings;

/**
 * Class Settings
 * @package App\Migration\Entities
 */
class Settings
{
    /**
     * @var MigrateSettings
     */
    protected $settings;

    /**
     * Settings constructor.
     * @param MigrateSettings $settings
     */
    public function __construct(MigrateSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        $SettingsData = [];

        foreach ($accountIds as $accountId) {
            $organization = getOrganizationFor($accountId);

            if ($organization) {
                $SettingsData[] = $this->settings->SettingsDataFetch($organization->id, $accountId);
            }
        }

        return $SettingsData;
    }
}
