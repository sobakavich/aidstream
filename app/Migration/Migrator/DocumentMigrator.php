<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Document;
use App\Models\Document as DocumentModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;

/**
 * Class DocumentMigrator
 * @package App\Migration\Migrator
 */
class DocumentMigrator implements MigratorContract
{
    /**
     * @var Document
     */
    protected $document;

    /**
     * @var DocumentModel
     */
    protected $documentModel;

    /**
     * DocumentMigrator constructor.
     * @param Document      $document
     * @param DocumentModel $documentModel
     */
    public function __construct(Document $document, DocumentModel $documentModel)
    {
        $this->document      = $document;
        $this->documentModel = $documentModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $database = app()->make(DatabaseManager::class);

        $organizationDocuments = $this->document->getData($accountIds);

        try {
            foreach ($organizationDocuments as $documents) {
                foreach ($documents[0] as $document) {
                    $newDocument = $this->documentModel->newInstance($document);

                    if (!$newDocument->save()) {
                        return 'Error during Documents table migration.';
                    }
                }
            }

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

        return 'Documents table migrated';
    }
}
