<?php namespace App\Services\XmlImporter\XmlImportManager;

use App\Services\XmlImporter\Xml;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class XmlImportManager
 * @package App\Services\XmlImporter\XmlImportManager
 */
class XmlImportManager
{
    /**
     * @var Xml
     */
    protected $xml;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * XmlImportManager constructor.
     *
     * @param Xml             $xml
     * @param LoggerInterface $logger
     */
    public function __construct(Xml $xml, LoggerInterface $logger)
    {
        $this->xml    = $xml;
        $this->logger = $logger;
    }

    /**
     * Import the Xml data.
     *
     * @param UploadedFile $file
     * @return bool|null
     */
    public function import(UploadedFile $file)
    {
        try {
            $xmlData = $this->xml->load(file_get_contents($file));

            dd($xmlData);
            return true;
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());

            return null;
        }

    }
}
