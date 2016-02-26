<?php namespace App\Migration\Entities;

use App\Migration\Migrator\Data\PublishToRegisterQuery;

/**
 * Class Settings
 * @package App\Migration\Entities
 */
class PublishToRegister
{
    /**
     * @var PublishToRegisterQuery
     */
    protected $publishToRegisterQuery;

    /**
     * Settings constructor.
     * @param PublishToRegisterQuery $publishToRegisterQuery
     */
    public function __construct(PublishToRegisterQuery $publishToRegisterQuery)
    {
        $this->publishToRegisterQuery = $publishToRegisterQuery;
    }

    /**
     * @param $accountIds
     * @return array
     */
    public function getData($filenameArrays)
    {
        return $this->publishToRegisterQuery->executeFor($filenameArrays);
    }
}
