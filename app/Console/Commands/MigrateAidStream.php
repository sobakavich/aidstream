<?php namespace App\Console\Commands;

use App\Migration\Migrator\ActivityMigrator;
use App\Migration\Entities\Activity;
use App\Migration\Migrator\DocumentMigrator;
use App\Migration\Migrator\OrganizationDataMigrator;
use App\Migration\Migrator\OrganizationMigrator;
use App\Migration\Migrator\SettingsMigrator;
use App\Migration\Migrator\TransactionMigrator;
use App\Migration\Migrator\ResultMigrator;
use App\Migration\Migrator\UserMigrator;
use App\Migration\Migrator\ActivityPublishedMigrator;
use App\Migration\Migrator\OrganizationPublishedMigrator;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MigrateAidStream
 * @package App\Console\Commands
 */
class MigrateAidStream extends Command
{
    /**
     * @var ActivityMigrator
     */
    protected $activityMigrator;

    /**
     * @var UserMigrator
     */
    protected $userMigrator;

    /**
     * @var OrganizationMigrator
     */
    protected $organizationMigrator;

    /**
     * @var DocumentMigrator
     */
    protected $documentMigrator;

    /**
     * @var SettingsMigrator
     */
    protected $settingsMigrator;

    /**
     * @var $ActivityPublishedMigrator
     */
    protected $activityPublishedMigrator;

    /**
     * @var $OrganizationPublishedMigrator
     */
    protected $organizationPublishedMigrator;

    /**
     * @var Activity
     */
    protected $activity;

    /**
     * Name for the command.
     *
     * @var string
     */
    protected $name = 'migrate-aidstream';

    /**
     * Description of the command.
     *
     * @var string
     */
    protected $description = 'Migrate Aidstream';

    /**
     * Signature for the command.
     * @var string
     */
    protected $signature = 'migrate-aidstream {table} {--country=} {--trace}';

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var OrganizationDataMigrator
     */
    protected $organizationDataMigrator;

    /**
     * @var TransactionMigrator
     */
    protected $transactionMigrator;

    /**
     * MigrateAidStream constructor.
     * @param ActivityMigrator         $activityMigrator
     * @param UserMigrator             $userMigrator
     * @param OrganizationMigrator     $organizationMigrator
     * @param DocumentMigrator         $documentMigrator
     * @param SettingsMigrator         $settingsMigrator
     * @param OrganizationDataMigrator $organizationDataMigrator
     * @param TransactionMigrator      $transactionMigrator
     * @param ResultMigrator           $resultMigrator
     * @param ActivityPublishedMigrator $activityPublishedMigrator
     * @param OrganizationPublishedMigrator $organizationPublishedMigrator
     * @param DatabaseManager          $databaseManager
     */
    public function __construct(
        ActivityMigrator $activityMigrator,
        UserMigrator $userMigrator,
        OrganizationMigrator $organizationMigrator,
        DocumentMigrator $documentMigrator,
        SettingsMigrator $settingsMigrator,
        OrganizationDataMigrator $organizationDataMigrator,
        TransactionMigrator $transactionMigrator,
        ResultMigrator $resultMigrator,
        ActivityPublishedMigrator $activityPublishedMigrator,
        OrganizationPublishedMigrator $organizationPublishedMigrator,
        DatabaseManager $databaseManager
    ) {
        parent::__construct();
        $this->activityMigrator              = $activityMigrator;
        $this->userMigrator                  = $userMigrator;
        $this->organizationMigrator          = $organizationMigrator;
        $this->documentMigrator              = $documentMigrator;
        $this->settingsMigrator              = $settingsMigrator;
        $this->databaseManager               = $databaseManager;
        $this->organizationDataMigrator      = $organizationDataMigrator;
        $this->activityPublishedMigrator     = $activityPublishedMigrator;
        $this->organizationPublishedMigrator = $organizationPublishedMigrator;
        $this->transactionMigrator           = $transactionMigrator;
        $this->resultMigrator                = $resultMigrator;
    }

    /**
     * Fire the artisan command.
     */
    public function fire()
    {
        $argument = $this->argument('table');
        $country  = $this->option('country');
        $trace    = $this->option('trace');

        try {
            $this->info('Running the migrations');

            $this->databaseManager->beginTransaction();
            $this->beginMigration($argument, $country);

            $this->databaseManager->commit();
        } catch (Exception $exception) {
            $this->rollback($exception, $trace);
        }
    }

    /**
     * Migrate all tables' data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateAll(array $accountIds)
    {
        $response   = [];
        $response[] = $this->organizationMigrator->migrate($accountIds);
        $response[] = $this->userMigrator->migrate($accountIds);
        $response[] = $this->documentMigrator->migrate($accountIds);
        $response[] = $this->settingsMigrator->migrate($accountIds);
        $response[] = $this->activityMigrator->migrate($accountIds);
        $response[] = $this->organizationDataMigrator->migrate($accountIds);
        $response[] = $this->transactionMigrator->migrate($accountIds);
        $response[] = $this->resultMigrator->migrate($accountIds);
        $response[] = $this->activityPublishedMigrator->migrate($accountIds);
        $response[] = $this->organizationPublishedMigrator->migrate($accountIds);

        return implode("\n", $response);
    }

    /**
     * Migrate Users table data into the new database.
     * @param array $accountIds
     * @return mixed|string
     */
    protected function migrateUser(array $accountIds)
    {
        return $this->userMigrator->migrate($accountIds);
    }

    /**
     * Migrate Organizations table data into the new database.
     * @param array $accountIds
     * @return mixed|string
     */
    protected function migrateOrganization(array $accountIds)
    {
        return $this->organizationMigrator->migrate($accountIds);
    }

    /**
     * Migrate Documents table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateDocument(array $accountIds)
    {
        return $this->documentMigrator->migrate($accountIds);
    }

    /**
     * Migrate Settings table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateSettings(array $accountIds)
    {
        return $this->settingsMigrator->migrate($accountIds);
    }

    /**
     * Migrate Activities table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateActivity(array $accountIds)
    {
        return $this->activityMigrator->migrate($accountIds);
    }

    /**
     * Migrate OrganizationData table data into the new database.
     * @param array $accountIds
     * @return string
     */
    protected function migrateOrganizationData(array $accountIds)
    {
        return $this->organizationDataMigrator->migrate($accountIds);
    }

    /**
     * Migrate activity transctions table
     * @param array $accountIds
     * @return string
     */
    protected function migrateTransaction(array $accountIds)
    {
        return $this->transactionMigrator->migrate($accountIds);
    }

    /**
     * Migrate activity results table
     * @param array $accountIds
     * @return string
     */
    protected function migrateResult(array $accountIds)
    {
        return $this->resultMigrator->migrate($accountIds);
    }

    /**
     * Migrate activity published table
     * @param array $accountIds
     * @return string
     */
    protected function migrateActivityPublished(array $accountIds)
    {
        return $this->activityPublishedMigrator->migrate($accountIds);
    }

    /**
     * Migrate activity published table
     * @param array $accountIds
     * @return string
     */
    protected function migrateOrganizationPublished(array $accountIds)
    {
        return $this->organizationPublishedMigrator->migrate($accountIds);
    }


    /**
     * Get the command options
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['country', null, InputOption::VALUE_OPTIONAL, 'Run the migration for an Organization of a specific country.', null],
            ['trace', null, InputOption::VALUE_OPTIONAL, 'Get the trace in case of errors during the migration process.', null]
        ];
    }

    /**
     * Get the command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['table', InputArgument::REQUIRED, "The table you want to migrate. 'all' if all tables are to be migrated."]
        ];
    }

    /**
     * Start the migrations.
     * @param $argument
     * @param $country
     */
    protected function beginMigration($argument, $country)
    {
        $accountIds = $this->getAccountIdsFor($country);

        if ($argument == 'all') {
            $this->info($this->migrateAll($accountIds));
        } else {
            $method = sprintf('migrate%s', $argument);
            $this->triggerSpecific($method, $accountIds);
        }
    }

    /**
     * Run migrations for a specific table.
     * @param       $method
     * @param array $accountIds
     */
    protected function triggerSpecific($method, array $accountIds)
    {
        if (!method_exists($this, $method)) {
            $this->error('The table you specified does not exist');
        } else {
            $this->info($this->$method($accountIds));
        }
    }

    /**
     * Roll the migrations back.
     * @param      $exception
     * @param null $trace
     */
    protected function rollback($exception, $trace = null)
    {
        $this->databaseManager->rollback();
        $this->warn('Rolling the migrations back.');

        if ($trace) {
            $this->error($exception->getTraceAsString());
        } else {
            $this->error($exception->getMessage());
        }
    }

    /**
     * Get accountIds of a specific country from the old database.
     * @param null $country
     * @return array
     */
    protected function getAccountIdsFor($country = null)
    {
        $accountIds = [];

        $builder = $this->databaseManager->connection('mysql')->table('account')->select('id');

        if ($country) {
            $countryName = implode(' ', explode('-', $country));
            $builder->where('address', 'like', '%' . $countryName . '%');
        }

        $accounts = $builder->get();

        array_walk(
            $accounts,
            function ($value, $index) use (&$accountIds) {
                $accountIds[] = $value->id;
            }
        );

        return $accountIds;

    }
}
