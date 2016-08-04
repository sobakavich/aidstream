<?php namespace App\Http\Controllers\File;


use App\Http\Controllers\Controller;
use App\Services\File\S3\FileManager;


class FileOperationController extends Controller
{
    protected $fileUploader;

    /**
     * FileOperationController constructor.
     * @param  $fileUploader
     */
    public function __construct(FileManager $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function display()
    {
        $filePath = substr(request()->getPathInfo(), 1);
        $file     = $this->getFile($filePath);

        return redirect()->to($file);
    }

    public function getFile($filePath)
    {
        return $this->fileUploader->get($filePath);
    }
}