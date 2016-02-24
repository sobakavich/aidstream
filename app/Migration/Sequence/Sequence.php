<?php namespace App\Migration\Sequence;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class Sequence
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    protected $tables = [];
    protected $sequences = [];
    protected $except = ['migrations', 'password_resets', ''];

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

    protected function extractSequenceNames()
    {
        foreach ($this->tables as $table) {
            $this->sequences[] = $this->extractSequenceFor($table);
        }

        return $this;
    }

    protected function extractSequenceFor($table)
    {
        return sprintf('%s_id_seq', $table);
    }

    protected function resetSequences()
    {
        $max = $this->getNewBuilder()->selectRaw("MAX(id)")->from('activity_data')->get();

        foreach ($this->tables as $table) {
            $sequence = $this->extractSequenceFor($table);

        }
    }

    protected function getNewBuilder()
    {
        return $this->databaseManager->connection()->query();
    }

    protected function latest($table)
    {
        $this->getNewBuilder()->selectRaw("MAX(id)")->from($table)->get();

    }
}
