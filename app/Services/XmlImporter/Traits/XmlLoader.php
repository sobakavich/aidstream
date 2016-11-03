<?php namespace App\Services\XmlImporter\Traits;

use Sabre\Xml\Service;

/**
 * Class XmlLoader
 * @package App\Services\XmlImporter\Traits
 */
trait XmlLoader
{
    /**
     * Load xml data from file into an array/object/string.
     *
     * @param $data
     * @return array|object|string
     */
    protected function loadXml($data)
    {
        $xmlService = new Service();
        
        return $xmlService->parse($data);
    }
}
