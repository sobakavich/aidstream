<?php

namespace App\Console\Commands;

use App\Core\V201\Repositories\MigrateDocumentLinks as MigrateDocumentLinksRepo;
use Illuminate\Console\Command;

class MigrateDocumentLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:document-link {--verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates Document Links from activity_data to activity_document_links table';
    /**
     * @var MigrateDocumentLinksRepo
     */
    protected $migrateDocumentLinksRepo;

    /**
     * Create a new command instance.
     *
     * @param MigrateDocumentLinksRepo $migrateDocumentLinksRepo
     */
    public function __construct(MigrateDocumentLinksRepo $migrateDocumentLinksRepo)
    {
        parent::__construct();
        $this->migrateDocumentLinksRepo = $migrateDocumentLinksRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('verify')) {
            $this->migrateDocumentLinksRepo->verify();

            return false;
        }
        if ($this->migrateDocumentLinksRepo->migrate()) {
            dump("Document Links migrated Successfully");
        } else {
            dump("Failed to migrate Document Links.");
        }
    }
}
