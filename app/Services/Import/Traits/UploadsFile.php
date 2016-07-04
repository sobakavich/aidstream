<?php namespace App\Services\Import\Traits;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait UploadsFile
 * @package App\Services\Import\Traits
 */
trait UploadsFile
{
    /**
     * Upload file into the server to the specified path.
     * @param              $filePath
     * @param UploadedFile $file
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function upload($filePath, UploadedFile $file)
    {
        return $file->move($filePath, $file->getClientOriginalName());
    }
}
