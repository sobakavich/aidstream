<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Generator\JsonController;

class GenerateFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generatefield:json {table : The name of table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates given table fieldname and its type';

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
        echo json_encode($this->jsonController->index($table));
    }
}
