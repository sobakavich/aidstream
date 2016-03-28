<?php namespace App\Services\tz\Manager;

use App\Models\Activity\Activity as ActivityModel;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;
use App\Core\tz\Repositories\Activity as ActivityRepo;

/**
 * Class Activity
 * @package App\Services\tz\Manager
 */
class Activity
{
    protected $activityRepo;
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var DatabaseManager
     */
    private $database;

    /**
     * @param Guard           $auth
     * @param Logger          $logger
     * @param DatabaseManager $database
     * @param ActivityRepo    $activityRepo
     */
    public function __construct(Guard $auth, Logger $logger, DatabaseManager $database, ActivityRepo $activityRepo)
    {
        $this->auth         = $auth;
        $this->logger       = $logger;
        $this->database     = $database;
        $this->activityRepo = $activityRepo;
    }

    /**
     * store activity identifier
     * @param array $activityData
     * @param       $organizationId
     * @return ActivityModel
     */
    public function store(array $activityData, $organizationId)
    {
        $identifier         = $activityData['iati_identifiers'][0];
        $title              = [["language" => "", "narrative" => $activityData['title']]];
        $descriptions       = $this->formatDescriptions($activityData['description'][0]);
        $participatingOrgs  = $this->formatParticipatingOrgs($activityData['participating_organization'][0]);
        $activityStatus     = $activityData['activity_status'];
        $sectors            = $this->formatSectors($activityData['sector_category_code']);
        $recipientCountries = $this->formatRecipientCountries($activityData['recipient_country']);
        $activityDate       = [["date" => $activityData['start_date'], "type" => "2", "narrative" => [["narrative" => "", "language" => ""]]]];
        !$activityData['end_date'] ?: $activityDate[] = ["date" => $activityData['end_date'], "type" => "4", "narrative" => [["narrative" => "", "language" => ""]]];

        $activity = [
            'identifier'                 => $identifier,
            'title'                      => $title,
            'description'                => $descriptions,
            'participating_organization' => $participatingOrgs,
            'activity_status'            => $activityStatus,
            'sector'                     => $sectors,
            'activity_date'              => $activityDate,
            'recipient_country'          => $recipientCountries,
            'organization_id'            => $organizationId,
            'default_field_values'       => $activityData['default_field_values']
        ];

        return $this->activityRepo->store($activity);
    }

    /**
     * format description according to iati data
     * @param $description
     * @return array
     */
    protected function formatDescriptions($description)
    {
        $descriptions = [["type" => "1", "narrative" => [["narrative" => $description['general'], "language" => ""]]]];
        !$description['objectives'] ?: $descriptions[] = ["type" => "2", "narrative" => [["narrative" => $description['objectives'], "language" => ""]]];
        !$description['target_groups'] ?: $descriptions[] = ["type" => "3", "narrative" => [["narrative" => $description['target_groups'], "language" => ""]]];

        return $descriptions;
    }

    /**
     * format participating organizations according to iati data
     * @param $participatingOrg
     * @return array
     */
    protected function formatparticipatingOrgs($participatingOrg)
    {
        $participatingOrgs = [];
        foreach ($participatingOrg['funding_organization'] as $fundingOrg) {
            $participatingOrgs[] = ["organization_role" => "1", "identifier" => "", "organization_type" => "", "narrative" => [["narrative" => $fundingOrg['organization_name'], "language" => ""]]];
        }
        foreach ($participatingOrg['implementing_organization'] as $implementingOrg) {
            $participatingOrgs[] = [
                "organization_role" => "4",
                "identifier"        => "",
                "organization_type" => "",
                "narrative"         => [["narrative" => $implementingOrg['organization_name'], "language" => ""]]
            ];
        }

        return $participatingOrgs;
    }

    /**
     * format sectors according to iati data
     * @param $codes
     * @return array
     */
    protected function formatSectors($codes)
    {
        $sectors = [];
        foreach ($codes as $code) {
            $sectors[] = [
                "sector_vocabulary"    => "",
                "sector_code"          => "",
                "sector_category_code" => $code,
                "sector_text"          => "",
                "percentage"           => "",
                "narrative"            => [["narrative" => "", "language" => ""]]
            ];
        }

        return $sectors;
    }

    /**
     * format recipient countries according to iati data
     * @param $countryCodes
     * @return array
     */
    protected function formatRecipientCountries($countryCodes)
    {
        $recipientCountries = [];
        foreach ($countryCodes as $recipientCountry) {
            $recipientCountries[] = ["country_code" => $recipientCountry, "percentage" => "", "narrative" => [["narrative" => "", "language" => ""]]];
        }

        return $recipientCountries;
    }

    public function getActivityData($id)
    {
        return $this->activityRepo->getActivityData($id);
    }

    public function getActivity($id)
    {
        $activityData = $this->getActivityData($id);

        $activityIdentifiers = [$activityData->identifier];
        $title               = $activityData->title[0]['narrative'];
        $descriptions        = [];
        $descriptionIndexes  = ['', 'general', 'objectives', 'target_groups'];
        foreach ($activityData->description as $description) {
            $descriptions[$descriptionIndexes[$description['type']]] = $description['narrative'][0]['narrative'];
        }
        $descriptions      = [$descriptions];
        $participatingOrgs = [];
        foreach ($activityData->participating_organization as $participatingOrg) {
            $orgRole                       = $participatingOrg['organization_role'] == 1 ? 'funding_organization' : 'implementing_organization';
            $participatingOrgs[$orgRole][] = $participatingOrg['narrative'][0]['narrative'];
        }
        $participatingOrgs = [$participatingOrgs];
        $activityStatus    = $activityData->activity_status;
        $sectorCodes       = [];
        foreach ($activityData->sector as $sector) {
            $sectorCodes[] = $sector['sector_category_code'];
        }
        $startDate = '';
        $endDate   = '';
        foreach ($activityData->activity_date as $activityDate) {
            $activityDate['type'] == 2 ? $startDate = $activityDate['date'] : $endDate = $activityDate['date'];
        }
        $recipientCountries = [];
        foreach ($activityData->recipient_country as $recipientCountry) {
            $recipientCountries[] = $recipientCountry['country_code'];
        }

        $activity = [
            "iati_identifiers"           => $activityIdentifiers,
            "title"                      => $title,
            "description"                => $descriptions,
            "participating_organization" => $participatingOrgs,
            "activity_status"            => $activityStatus,
            "sector_category_code"       => $sectorCodes,
            "start_date"                 => $startDate,
            "end_date"                   => $endDate,
            "recipient_country"          => $recipientCountries
        ];

        return $activity;
    }

    public function update(array $activityData, ActivityModel $activityModel)
    {
        $identifier         = $activityData['iati_identifiers'][0];
        $title              = [["language" => "", "narrative" => $activityData['title']]];
        $descriptions       = $this->formatDescriptions($activityData['description'][0]);
        $participatingOrgs  = $this->formatParticipatingOrgs($activityData['participating_organization'][0]);
        $activityStatus     = $activityData['activity_status'];
        $sectors            = $this->formatSectors($activityData['sector_category_code']);
        $recipientCountries = $this->formatRecipientCountries($activityData['recipient_country']);
        $activityDate       = [["date" => $activityData['start_date'], "type" => "2", "narrative" => [["narrative" => "", "language" => ""]]]];
        !$activityData['end_date'] ?: $activityDate[] = ["date" => $activityData['end_date'], "type" => "4", "narrative" => [["narrative" => "", "language" => ""]]];

        $activity = [
            'identifier'                 => $identifier,
            'title'                      => $title,
            'description'                => $descriptions,
            'participating_organization' => $participatingOrgs,
            'activity_status'            => $activityStatus,
            'sector'                     => $sectors,
            'activity_date'              => $activityDate,
            'recipient_country'          => $recipientCountries
        ];

        return $this->activityRepo->update($activity, $activityModel);
    }
}
