<?php namespace App\Services\RequestManager\Activity;

use App\Core\Version;

/**
 * Class ImportResult
 * @package App\Services\RequestManager\Activity
 */
class ImportResult
{

    /**
     * @param Version $version
     */
    function __construct(Version $version)
    {
        return $version->getActivityElement()->getImportResultRequest();
    }
}
