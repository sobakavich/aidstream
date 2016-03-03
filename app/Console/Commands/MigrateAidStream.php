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
use App\Migration\Migrator\UserGroupMigrator;
use App\Migration\Migrator\PublishToRegisterMigrator;
use App\Migration\Sequence\Sequence;
use Carbon\Carbon;
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
     * @var ActivityPublishedMigrator
     */
    protected $activityPublishedMigrator;

    /**
     * @var OrganizationPublishedMigrator
     */
    protected $organizationPublishedMigrator;

    /**
     * @var ResultMigrator
     */
    protected $resultMigrator;

    /**
     * @var PublishToRegisterMigrator
     */
    protected $publishToRegisterMigrator;

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
    protected $signature = 'migrate-aidstream {table} {--country=} {--trace} {--reset-sequence}';

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
     * @var UserGroupMigrator
     */
    protected $userGroupMigrator;

    /**
     * MigrateAidStream constructor.
     * @param ActivityMigrator              $activityMigrator
     * @param UserMigrator                  $userMigrator
     * @param OrganizationMigrator          $organizationMigrator
     * @param DocumentMigrator              $documentMigrator
     * @param SettingsMigrator              $settingsMigrator
     * @param OrganizationDataMigrator      $organizationDataMigrator
     * @param TransactionMigrator           $transactionMigrator
     * @param ResultMigrator                $resultMigrator
     * @param ActivityPublishedMigrator     $activityPublishedMigrator
     * @param OrganizationPublishedMigrator $organizationPublishedMigrator
     * @param UserGroupMigrator             $userGroupMigrator
     * @param PublishToRegisterMigrator     $publishToRegisterMigrator
     * @param DatabaseManager               $databaseManager
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
        UserGroupMigrator $userGroupMigrator,
        PublishToRegisterMigrator $publishToRegisterMigrator,
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
        $this->userGroupMigrator             = $userGroupMigrator;
        $this->publishToRegisterMigrator     = $publishToRegisterMigrator;
    }

    /**
     * Fire the artisan command.
     */
    public function fire()
    {
        $argument       = $this->argument('table');
        $country        = $this->option('country');
        $trace          = $this->option('trace');
        $sequenceOption = $this->option('reset-sequence');

        try {
            if ($argument == 'reset-sequence') {
                $sequence = new Sequence();

                $this->info("\nSynchronizing all Sequences");

                $sequence->synchronize($this->databaseManager);

                return 'All Sequences synchronized';
            }

            $this->info('Running the migrations');

            $this->databaseManager->beginTransaction();
            $this->beginMigration($argument, $country);

            if ($sequenceOption) {
                $sequence = new Sequence();

                $this->info("\nSynchronizing all Sequences");

                $sequence->synchronize($this->databaseManager);
            }

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
        $this->informFor('Organization');

        $response[] = $this->userMigrator->migrate($accountIds);
        $this->informFor('User');

        $response[] = $this->documentMigrator->migrate($accountIds);
        $this->informFor('Documents');

        $response[] = $this->settingsMigrator->migrate($accountIds);
        $this->informFor('Settings');

        $response[] = $this->activityMigrator->migrate($accountIds);
        $this->informFor('Activities');

        $response[] = $this->organizationDataMigrator->migrate($accountIds);
        $this->informFor('OrganizationData');

        $response[] = $this->transactionMigrator->migrate($accountIds);
        $this->informFor('Transaction');

        $response[] = $this->resultMigrator->migrate($accountIds);
        $this->informFor('Results');

        $response[] = $this->activityPublishedMigrator->migrate($accountIds);
        $this->informFor('ActivityPublished');

        $response[] = $this->organizationPublishedMigrator->migrate($accountIds);
        $this->informFor('Organization Published');

        $response[] = $this->userGroupMigrator->migrate($accountIds);

        $this->info("\n");

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
     * Migrate user group table
     * @param array $accountIds
     * @return string
     */
    protected function migrateUserGroup(array $accountIds)
    {
        return $this->userGroupMigrator->migrate($accountIds);
    }

    /**
     * Migrate PublishToRegister
     * @param array $accountIds
     * @return string
     */
    protected function migrateRegistry(array $accountIds)
    {
        return $this->publishToRegisterMigrator->migrate($accountIds);
    }


    /**
     * Get the command options
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['country', null, InputOption::VALUE_OPTIONAL, 'Run the migration for an Organization of a specific country.', null],
            ['trace', null, InputOption::VALUE_OPTIONAL, 'Get the trace in case of errors during the migration process.', null],
            ['reset-sequence', null, InputOption::VALUE_OPTIONAL, 'Reset the sequences for the migrated tables.', null]
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
        return [];

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

    protected function informFor($table)
    {
        $this->info(sprintf('%s table migrated -- %s', $table, Carbon::now()));
    }
}
