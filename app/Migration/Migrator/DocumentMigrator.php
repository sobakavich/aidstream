<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Document;
use App\Models\Document as DocumentModel;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\File;

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
        $documentSqlQueries = [];

        try {
            foreach ($organizationDocuments as $index => $documents) {
                foreach ($documents as $key => $document) {
                    $newDocument = $this->documentModel->newInstance($document);

                    if (!$newDocument->save()) {
                        return 'Error during Documents table migration.';
                    }

//                    if (count($document) > 1) {
//                        foreach ($document as $d) {
//                            if (!empty($d)) {
//                                $query = sprintf("insert into documents values (%s)", implode(',', $d));
//                                $documentSqlQueries[] = $query;
//                            }
//                        }
//                    } else {
////                        try {
//                            if (!empty($document)) {
//                                $query = sprintf("insert into documents values (%s)", implode(',', array_values($document)[0]));
//                                $documentSqlQueries[] = $query;
//                            }
//
////                        } catch (\Exception $e) {
////                            dd($document);
////                        }
//
//                    }


//                    else {

//                    }

                }
            }

            $database->commit();
        } catch (\Exception $e) {
            $database->rollback();

            throw $e;
        }

//        File::put('missingDocumentsSql.txt', implode("\n", $documentSqlQueries));
        return 'Documents table migrated';
    }
}
