<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Settings;
use App\Migration\Migrator\Contract\MigratorContract;
use App\Models\Settings as SettingsModel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\File;

/**
 * Class SettingsMigrator
 * @package App\Migration\Migrator
 */
class SettingsMigrator implements MigratorContract
{
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var SettingsModel
     */
    protected $settingsModel;

    /**
     * SettingsMigrator constructor.
     * @param Settings      $settings
     * @param SettingsModel $settingsModel
     */
    public function __construct(Settings $settings, SettingsModel $settingsModel)
    {
        $this->settings      = $settings;
        $this->settingsModel = $settingsModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);
        $settingsSqlQueries = [];
        $settingsData = $this->settings->getData($accountIds);

        try {
            foreach ($settingsData as $setting) {
                $data = [];
                foreach ($setting as $key => $s) {
                    $data[$key] = is_array($s) ? json_encode($s) : $s;


                }

                //dd($data);
                $query = sprintf("insert into settings values (%s)", implode(',', $data));
                $settingsSqlQueries[] = $query;
             //   $settingSqlQueries[] = $query;
//                $newSettings = $this->settingsModel->newInstance($setting);

//                if (!$newSettings->save()) {
//                    return 'Error during Settings table migration.';
//                }
            }
           // dd($settingsSqlQueries);
            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

        File::put('missingSettingsSql.txt', implode("\n", $settingsSqlQueries));
       // return 'Documents table migrated';
        return 'Settings table migrated';
    }
}
