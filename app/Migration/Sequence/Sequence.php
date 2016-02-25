<?php namespace App\Migration\Sequence;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

/**
 * Class Sequence
 * @package App\Migration\Sequence
 */
class Sequence
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * @var array
     */
    protected $except = ['migrations', 'password_resets', 'versions', 'user_activities', 'jobs', 'failed_jobs', 'role'];

    /**
     * @param DatabaseManager $databaseManager
     */
    public function synchronize(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
        $builder               = $this->getNewBuilder();

        $this->getTablesNames(
            $builder->selectRaw("* from information_schema.tables")
                    ->where('table_schema', '=', 'public')
                    ->get()
        );

        $this->resetSequences();
    }

    /**
     * @param $tables
     * @return $this
     */
    protected function getTablesNames($tables)
    {
        array_walk(
            $tables,
            function ($value) {
                $this->tables[] = $value->table_name;
            }
        );

        return $this;
    }

    /**
     * @param $table
     * @return string
     */
    protected function extractSequenceNameFor($table)
    {
        return sprintf('%s_id_seq', $table);
    }

    /**
     * Reset Sequences for all migrated tables.
     */
    protected function resetSequences()
    {
        foreach (array_diff($this->tables, $this->except) as $table) {
            $sequenceName         = $this->extractSequenceNameFor($table);
            $lastRecordIndex      = $this->latest($table)->index;
            if ($lastRecordIndex) {
                DB::statement("SELECT setval('$sequenceName', $lastRecordIndex, true)");
            }
        }
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getNewBuilder()
    {
        return $this->databaseManager->connection()->query();
    }

    /**
     * @param $table
     * @return mixed|static
     */
    protected function latest($table)
    {
        return $this->getNewBuilder()->selectRaw("MAX(id) as index")->from($table)->first();
    }
}
