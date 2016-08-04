<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Services\File\S3\FileManager;
use Aws\S3\Exception\S3Exception;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DocumentsS3Migration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Document
     */
    protected $documents;

    /**
     * @var FileManager
     */
    protected $fileManager;

    protected $docLog = [];

    protected $failedLog = [];
    const S3_Document_Path = 'https://s3-us-west-2.amazonaws.com/aidstream-demo/';

    /**
     * Create a new command instance.
     *
     * @param Document    $document
     * @param FileManager $fileManager
     */
    public function __construct(Document $document, FileManager $fileManager)
    {
        parent::__construct();
        $this->documents   = $document;
        $this->fileManager = $fileManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->migrateDocuments();
    }

//    public function migrateRemainingDocuments()
//    {
//        $documents = $this->documents->all();
//        foreach ($documents as $document) {
//            $filename      = $document->filename;
//            $localFilePath = $this->getLocalDocumentPath($filename);
//            $file          = $this->loadLocalFile($localFilePath, $document->id);
//            $orgId         = $document->org_id;
//            $toMigratePath = self::S3_Document_Path . $this->getS3DocumentPath($orgId, $filename);
//            echo $filename . PHP_EOL;
//            if (!$file) {
//                $file['status'] = false;
//            }
//
//            if (strpos('%', $filename)) {
//                dd($filename);
//            }
////            $fileCheck = $this->checkIfFileExistsInS3($toMigratePath);
////            echo $fileCheck . PHP_EOL;
////            if ($fileCheck) {
////                $this->logArray($orgId, $filename, $localFilePath, $toMigratePath, $file['status'], 'true');
////                $this->generateJson('SuccessfullyMigrated.json');
////                continue;
////            }
////            $migrationStatus = $this->transferToS3($toMigratePath, $document, $file);
////            echo $migrationStatus . PHP_EOL;
////            $this->logArray($orgId, $filename, $localFilePath, $toMigratePath, $file['status'], $migrationStatus);
////            $this->generateJson('DocumentMigration2.json');
////
////            $document->url = $toMigratePath;
////            $document->save();
//        }
//    }

    public function migrateDocuments()
    {
        $documents = $this->documents->all();
        echo date('H:i:s') . PHP_EOL;

        foreach ($documents as $document) {
            $filename      = $document->filename;
            $localFilePath = $this->getLocalDocumentPath($filename);
            $file          = $this->loadLocalFile($localFilePath);
            $orgId         = $document->org_id;
            $s3Path        = $this->getS3DocumentPath($orgId, $filename);
            $toMigratePath = self::S3_Document_Path . $s3Path;
            $dirPath       = $this->getDirPath($orgId);
            $docId         = $document->id;

            if (is_null($filename)) {
                continue;
            }

            if (!$file) {
                echo $file;
                $file['status']            = false;
                $file['size']              = 0;
                $this->failedLog[$orgId][] = $this->logArray($filename, $localFilePath, $toMigratePath, $file['status'], false, $docId, $file['size']);
                $this->generateJson('FileNotFound.json', $this->failedLog);
                continue;
            }

            $migrationStatus = $this->transferToS3($dirPath, $s3Path, $document, $file);

            $this->docLog[$orgId][] = $this->logArray($filename, $localFilePath, $toMigratePath, $file['status'], $migrationStatus, $docId, $file['size']);
            $this->generateJson('DocumentMigration.json', $this->docLog);
            $document->url = $toMigratePath;
            $document->save();
        }
        echo date('H:i:s') . PHP_EOL;
    }

    protected function transferToS3($dirPath, $s3Path, $document, $file)
    {
        $migrationStatus = '';
        if (isset($file['file']) && isset($file['size'])) {
            echo $document->id . ' Preparing to transfer file: ' . $document->filename . PHP_EOL;
            try {
                $this->fileManager->makeDir($dirPath);
                $migrationStatus = $this->fileManager->put($s3Path, $file['file'], 'public');
                echo $document->filename . ' has been successfully transferred to S3' . PHP_EOL;
            } catch (S3Exception $exception) {
                $migrationStatus = 'Failed to write in S3 due to ' . $exception->getMessage();
                echo $migrationStatus . PHP_EOL;
                $this->transferToS3($dirPath, $s3Path, $document, $file);
                $migrationStatus = true;
            }
        }

        return $migrationStatus;
    }

    protected function logArray($filename, $localFilePath, $toMigratePath, $status, $migrationStatus, $docId, $size)
    {
        return [
            'docId'             => $docId,
            'filename'          => $filename,
            'size'              => $size,
            'localFilePath'     => (string) $localFilePath,
            'toMigratePath'     => (string) $toMigratePath,
            'fileStatus'        => $status,
            's3MigrationStatus' => $migrationStatus
        ];
    }

    protected function loadLocalFile($filePath)
    {
        $response = false;
        try {
            if (File::exists($filePath)) {
                $file     = file_get_contents($filePath);
                $fileSize = File::size($filePath);
                $response = [
                    'file'   => $file,
                    'size'   => $fileSize,
                    'status' => true
                ];
            }
        } catch (Exception $exception) {

            return false;
        }

        return $response;
    }

    protected function generateJson($filename, Array $array)
    {
        file_put_contents(sprintf('%s/%s', public_path(), $filename), json_encode($array, JSON_PRETTY_PRINT));
    }

    protected function getS3DocumentPath($orgId, $filename)
    {
        return sprintf('%s/%s/%s', 'documents', $orgId, $filename);
    }

    protected function getLocalDocumentPath($filename)
    {
        return sprintf('%s/%s/%s', public_path(), 'files/documents', $filename);
    }

    protected function getDirPath($orgId)
    {
        return sprintf('%s/%s', 'documents', $orgId);
    }

    protected function checkIfFileExistsInS3($filePath)
    {
        try {
            return $this->fileManager->has($filePath);
        } catch (S3Exception $exception) {
            echo $exception->getMessage();
            $this->checkIfFileExistsInS3($filePath);
        }

    }
}
