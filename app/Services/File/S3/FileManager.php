<?php namespace App\Services\File\S3;

use App\Services\File\Contracts\FileManagerInterface;
use Illuminate\Filesystem\FilesystemManager;

class FileManager implements FileManagerInterface
{
    use S3Trait;

    protected $filesystemManager;

    protected $disk;

    protected $client;

    protected $url;

    protected $orgId;

    public function __construct(FilesystemManager $filesystemManager)
    {
        $this->filesystemManager = $filesystemManager;
        $this->disk              = $this->getDisk();
        $this->client            = $this->getS3Client();
        $this->orgId             = session('org_id');
    }



    /**
     * Store the file
     * @param $filePath
     * @param $file
     * @param $visibility
     * @return mixed
     */
    public function put($filePath, $file, $visibility = null)
    {
        return $this->disk->put($filePath, $file, $visibility);
    }

    /**
     * Fetch the file
     * @return mixed
     */
    public function get($filePath)
    {
        $bucket = $this->getBucket();

        return $this->getUrl($bucket, $filePath);
    }

    /**
     * Remove the file
     * @param $filePath
     * @return mixed
     */
    public function delete($filePath)
    {
        return $this->disk->delete($filePath);
    }

    /**
     * Create a directory
     * @param $filePath
     * @return mixed
     */
    public function makeDir($filePath)
    {
        return $this->disk->makeDirectory($filePath);
    }

    public function has($filePath)
    {
        return $this->disk->exists($filePath);
    }

    public function getXmlFilePath($filename)
    {
        return sprintf('%s/%s/%s', 'xml', $this->orgId, $filename);
    }

    public function getActivityXmlFilePath($filename)
    {
        return sprintf('%s/%s/%s/%s', 'xml', $this->orgId, 'activities', $filename);
    }

    public function getDocumentFilePath($filename)
    {
        return sprintf('%s/%s/%s', 'documents', $this->orgId, $filename);
    }
}