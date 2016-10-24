<?php

namespace App\Console\Commands;

use App\Http\Controllers\Generator\JsonController;
use Illuminate\Console\Command;

class GenerateDatas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generatedata:json {table} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates fieldname and data for given table name and id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $jsonController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(JsonController $jsonController)
    {
        parent::__construct();
        $this->jsonController = $jsonController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = $this->argument('table');
        $id    = $this->argument('id');
        echo json_encode($this->jsonController->showData($table, $id));
    }
}
