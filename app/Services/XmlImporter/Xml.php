<?php namespace App\Services\XmlImporter;

use App\Services\XmlImporter\Traits\XmlLoader;

/**
 * Class Xml
 * @package App\Services\XmlImporter
 */
class Xml
{
    use XmlLoader;

    /**
     * @var
     */
    protected $xml;

    /**
     * @var
     */
    protected $attributes;

    /**
     * @var
     */
    protected $activity;

    /**
     * Xml constructor.
     */
    public function __construct()
    {

    }

    /**
     * Load xml data into an array|object|string.
     *
     * @param $data
     * @return array|object|string
     */
    public function load($data)
    {
        return $this->loadXml($data);
    }
}
