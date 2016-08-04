<?php namespace App\Services\File\Contracts;


interface FileManagerInterface
{
    /**
     * Store the file
     * @param      $filename
     * @param      $file
     * @param null $visibility
     * @return mixed
     */
    public function put($filename, $file, $visibility = null);

    /**
     * Remove the file
     * @param $filePath
     * @return mixed
     */
    public function delete($filePath);

    /**
     * Fetch the file
     * @param $filePath
     * @return mixed
     */
    public function get($filePath);

    /**
     * @param $filePath
     * @return mixed
     */
    public function makeDir($filePath);

    /**
     * @param $filePath
     * @return mixed
     */
    public function has($filePath);

}