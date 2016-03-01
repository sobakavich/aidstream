<?php namespace App\Migration\Migrator\Data;

use App\Migration\ActivityData;
use App\Migration\Elements\ActivityDate;
use App\Migration\Elements\ActivityDocumentLink;
use App\Migration\Elements\Budget;
use App\Migration\Elements\Condition;
use App\Migration\Elements\ContactInfo;
use App\Migration\Elements\CountryBudgetItem;
use App\Migration\Elements\Description;
use App\Migration\Elements\Identifier;
use App\Migration\Elements\LegacyData;
use App\Migration\Elements\Location;
use App\Migration\Elements\OtherIdentifier;
use App\Migration\Elements\ParticipatingOrganization;
use App\Migration\Elements\PlannedDisbursement;
use App\Migration\Elements\PolicyMarker;
use App\Migration\Elements\RecipientCountry;
use App\Migration\Elements\RecipientRegion;
use App\Migration\Elements\RelatedActivity;
use App\Migration\Elements\Sector;
use App\Migration\Elements\Title;

/**
 * Class ActivityQuery
 * @package App\Migration\Migrator\Data
 */
class ActivityQuery extends Query
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ActivityData
     */
    protected $activityData;
    /**
     * @var Title
     */
    protected $title;

    /**
     * @var Identifier
     */
    protected $identifier;

    /**
     * @var OtherIdentifier
     */
    protected $otherIdentifier;

    /**
     * @var Description
     */
    protected $description;

    /**
     * @var ActivityDate
     */
    protected $activityDate;

    /**
     * @var ParticipatingOrganization
     */
    protected $participatingOrganization;

    /**
     * @var RecipientCountry
     */
    protected $recipientCountry;

    /**
     * @var RecipientRegion
     */
    protected $recipientRegion;

    /**
     * @var Sector
     */
    protected $sector;

    /**
     * ActivityQuery constructor.
     * @param ActivityData              $activityData
     * @param Title                     $title
     * @param Identifier                $identifier
     * @param OtherIdentifier           $otherIdentifier
     * @param Description               $description
     * @param ActivityDate              $activityDate
     * @param ParticipatingOrganization $participatingOrganization
     * @param RecipientCountry          $recipientCountry
     * @param RecipientRegion           $recipientRegion
     * @param Sector                    $sector
     */
    public function __construct(
        ActivityData $activityData,
        Title $title,
        Identifier $identifier,
        OtherIdentifier $otherIdentifier,
        Description $description,
        ActivityDate $activityDate,
        ParticipatingOrganization $participatingOrganization,
        RecipientCountry $recipientCountry,
        RecipientRegion $recipientRegion,
        Sector $sector
    ) {
        $this->activityData              = $activityData;
        $this->title                     = $title;
        $this->identifier                = $identifier;
        $this->otherIdentifier           = $otherIdentifier;
        $this->description               = $description;
        $this->activityDate              = $activityDate;
        $this->participatingOrganization = $participatingOrganization;
        $this->recipientCountry          = $recipientCountry;
        $this->recipientRegion           = $recipientRegion;
        $this->sector                    = $sector;
    }

    /**
     * @param array $accountIds
     * @return array
     */
    public function executeFor(array $accountIds)
    {
        $data = [];
        $this->initDBConnection();

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getData($organization->id, $accountId);
            }
        }

        return $data;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return array
     */
    protected function getData($organizationId, $accountId)
    {
        $activities = $this->activityData->getActivitiesFor($organizationId);
        $this->data = [];

        foreach ($activities as $activity) {
            $activityId                                   = $activity->id;
            $this->data[$activityId]['organization_id']   = $accountId;
            $this->data[$activityId]['id']                = $activityId;
            $activityStatus                               = $this->activityData->getActivityWorkflowFor($activityId)->status_id;
            $this->data[$activityId]['activity_workflow'] = $activityStatus ? ($activityStatus - 1) : 0;
            $this->data[$activityId]['created_at']        = $activity->updated_at;
            $this->data[$activityId]['updated_at']        = $activity->updated_at;

            $this->titleDataFetch($activityId)
                 ->fetchIdentifier($activityId)
                 ->fetchOtherIdentifier($activityId)
                 ->fetchDescription($activityId)
                 ->fetchActivityStatus($activityId)
                 ->fetchActivityDate($activityId)
                 ->fetchParticipatingOrganization($activityId)
                 ->fetchRecipientCountry($activityId)
                 ->fetchRecipientRegion($activityId)
                 ->fetchSector($activityId)
                 ->fetchContactInfo($activityId)
                 ->fetchActivityScope($activityId)
                 ->fetchLocation($activityId)
                 ->fetchPolicyMarker($activityId)
                 ->fetchCollaborationType($activityId)
                 ->fetchDefaultFlowType($activityId)
                 ->fetchDefaultFinanceType($activityId)
                 ->fetchDefaultAidType($activityId)
                 ->fetchDefaultTiedStatus($activityId)
                 ->fetchCountryBudgetItems($activityId)
                 ->fetchCapitalSpend($activityId)
                 ->fetchPlannedDisbursement($activityId)
                 ->fetchLegacyData($activityId)
                 ->fetchRelatedActivity($activityId)
                 ->fetchBudgetData($activityId)
                 ->fetchConditions($activityId)
                 ->fetchDocumentLink($activityId)
                 ->fetchDefaultFieldValues($activity);
        }

        return $this->data;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function titleDataFetch($activityId)
    {
        $activityData              = [];
        $activityData[$activityId] = ['title' => '', 'lang' => ''];

        $iatiTitle = $this->connection->table('iati_title')
                                      ->select('id')
                                      ->where('activity_id', '=', $activityId)
                                      ->first();

        if ($iatiTitle) {
            $titleInfo = $this->connection->table('iati_title/narrative')
                                          ->select('text', '@xml_lang as xml_lang')
                                          ->where('title_id', '=', $iatiTitle->id)
                                          ->get();

            //get lang from xml_lang code
            $lang_from_query = [];

            foreach ($titleInfo as $title) {
                $lang              = $title->xml_lang;
                $lang_from_query[] = getLanguageCodeFor($lang);

                $activityData[$activityId] = ['title' => $titleInfo, 'lang' => $lang_from_query];
            }
        }

        $formattedData                    = $this->title->format($activityData);
        $this->data[$activityId]['title'] = $formattedData;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchIdentifier($activityId)
    {
        $iatiIdentifierInfo = $this->connection->table('iati_identifier')
                                               ->select('activity_identifier', 'text')
                                               ->where('activity_id', '=', $activityId)
                                               ->first();

        //array of activity data
        $this->data[$activityId]['identifier'] = $this->identifier->format($iatiIdentifierInfo);

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchOtherIdentifier($activityId)
    {
        $iatiOtherInfo = $this->connection->table('iati_other_identifier')
                                          ->select('@ref as ref', '@type as type', 'id')
                                          ->where('activity_id', '=', $activityId)
                                          ->first();

        if (!is_null($iatiOtherInfo)) {
            $type_id   = $iatiOtherInfo->type;
            $type_code = $this->connection->table('OtherIdentifierType')
                                          ->select('Code')
                                          ->where('id', '=', $type_id)
                                          ->first();

            $iatiOtherIdentifierOwnerOrg = $this->connection->table('iati_other_identifier/ownerorg')
                                                            ->select('id', '@ref as owner_org_ref')
                                                            ->where('other_activity_identifier_id', '=', $iatiOtherInfo->id)
                                                            ->first();

            if (!is_null($iatiOtherIdentifierOwnerOrg)) {
                $ownerOrgReference = $iatiOtherIdentifierOwnerOrg->owner_org_ref;
                $id_owner_org      = $iatiOtherIdentifierOwnerOrg->id;

                $iatiOtherIdentifierNarrative = $this->connection->table('iati_other_identifier/ownerorg/narrative')
                                                                 ->select('text', '@xml_lang as xml_lang')
                                                                 ->where('owner_org_id', '=', $id_owner_org)
                                                                 ->get();
                $narrativeArray               = [];

                if ($iatiOtherIdentifierNarrative) {
                    foreach ($iatiOtherIdentifierNarrative as $eachNarrative) {
                        $lang_id          = $eachNarrative->xml_lang;
                        $lang_code        = getLanguageCodeFor($lang_id);
                        $narrativeArray[] = ['narrative' => $eachNarrative->text, 'language' => $lang_code];
                    }
                } else {
                    $narrativeArray = [
                        [
                            'narrative' => '',
                            'language'  => ''
                        ]
                    ];
                }

                $otherIdentifierData = [
                    'ownerOrgReference' => $ownerOrgReference,
                    'narratives'        => $narrativeArray,
                    'iatiOtherInfo'     => $iatiOtherInfo,
                    'typeCode'          => $type_code
                ];

                $this->data[$activityId]['other_identifier'] = $this->otherIdentifier->format($otherIdentifierData);
            }
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchDescription($activityId)
    {
        $descriptions    = $this->connection->table('iati_description')
                                            ->select('id', '@type as type')
                                            ->where('activity_id', '=', $activityId)
                                            ->get();
        $dataDescription = null;

        foreach ($descriptions as $description) {
            $typeCode = "";
            $descType = $this->connection->table('iati_description')
                                         ->select('@type as type')
                                         ->where('id', '=', $description->id)
                                         ->first();
            $typeId   = $descType->type;

            if ($typeId != "") {
                $typeCode = ($descriptionType = $this->connection->table('DescriptionType')
                                                                 ->select('Code')
                                                                 ->where('id', '=', $typeId)
                                                                 ->first()) ? $descriptionType->Code : '';
            }

            $descriptionNarratives = $this->connection->table('iati_description/narrative')
                                                      ->select('*', '@xml_lang as xml_lang_id')
                                                      ->where('description_id', '=', $description->id)
                                                      ->get();

            $dataDescription[] = $this->description->format($descriptionNarratives, $typeCode);
        }

        if (!is_null($descriptions)) {
            $this->data[$activityId]['description'] = $dataDescription;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchActivityStatus($activityId)
    {
        $activity_status = $this->connection->table('iati_activity_status')
                                            ->select('@code as code')
                                            ->where('activity_id', '=', $activityId)
                                            ->first();
        $activityStatus  = null;

        if (!is_null($activity_status)) {
            $activityStatus = $activity_status->code;
        }

        $this->data[$activityId]['activity_status'] = $activityStatus;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchActivityDate($activityId)
    {
        $dataActivityDate        = null;
        $activity_date_instances = $this->connection->table('iati_activity_date')
                                                    ->select('*', '@iso_date as iso_date', '@type as type')
                                                    ->where('activity_id', '=', $activityId)
                                                    ->get();

        foreach ($activity_date_instances as $dateInfo) {
            $isoDate            = $dateInfo->iso_date;
            $ActivityDateTypeId = $dateInfo->type;

            $ActivityDateTypeCode = ($FetchActivityDateTypeCode = $this->connection->table('ActivityDateType')
                                                                                   ->select('Code')
                                                                                   ->where('id', '=', $ActivityDateTypeId)
                                                                                   ->first()) ? $FetchActivityDateTypeCode->Code : '';
            $dateNarratives       = $this->connection->table('iati_activity_date/narrative')
                                                     ->select('*', '@xml_lang as xml_lang')
                                                     ->where('activity_date_id', '=', $dateInfo->id)
                                                     ->get();

            $dataActivityDate[] = $this->activityDate->format($dateNarratives, $isoDate, $ActivityDateTypeCode);
        }

        if (!is_null($activity_date_instances)) {
            $this->data[$activityId]['activity_date'] = $dataActivityDate;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchParticipatingOrganization($activityId)
    {
        $dataParticipatingOrg = null;
        $language             = "";

        $participating_org_instances = $this->connection->table('iati_participating_org')
                                                        ->select('@role as role', '@type as type', '@ref as ref', 'id', 'activity_id')
                                                        ->where('activity_id', '=', $activityId)
                                                        ->get();

        foreach ($participating_org_instances as $participatingOrgInfo) {
            $OrgType_Id = $participatingOrgInfo->type;

            if ($OrgType_Id != "") {
                $OrgTypeCode = ($fetchOrgTypeCode = $this->connection->table('OrganisationType')
                                                                     ->select('Code')
                                                                     ->where('id', '=', $OrgType_Id)
                                                                     ->first()) ? $fetchOrgTypeCode->Code : '';
            } else {
                $OrgTypeCode = '';
            }

            $OrgType_Id = $participatingOrgInfo->type;

            $Identifier       = $participatingOrgInfo->ref;
            $OrgRoleId        = $participatingOrgInfo->role;
            $FetchOrgRoleCode = $this->connection->table('OrganisationRole')
                                                 ->select('Code')
                                                 ->where('id', '=', $OrgRoleId)
                                                 ->first();

            $OrgRoleCode = $FetchOrgRoleCode ? $FetchOrgRoleCode->Code : '';

            $ParticipatingOrgNarratives = $this->connection->table('iati_participating_org/narrative')
                                                           ->select('*', '@xml_lang as xml_lang')
                                                           ->where('participating_org_id', '=', $participatingOrgInfo->id)
                                                           ->get();

            $Narrative = [];
            foreach ($ParticipatingOrgNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;

                if ($eachNarrative->xml_lang == "") {
                    $language = '';
                } else {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataParticipatingOrg[] = $this->participatingOrganization->format($ParticipatingOrgNarratives, $OrgRoleCode, $Identifier, $OrgTypeCode, $Narrative);
        }

        if (!is_null($participating_org_instances)) {
            $this->data[$activityId]['participating_organization'] = $dataParticipatingOrg;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchRecipientCountry($activityId)
    {
        $dataRecipientCountry  = null;
        $language              = "";
        $recipientOrgInstances = $this->connection->table('iati_recipient_country')
                                                  ->select('*', '@code as code', '@percentage as percentage')
                                                  ->where('activity_id', '=', $activityId)
                                                  ->get();

        foreach ($recipientOrgInstances as $recipientOrgInfo) {
            $recipientCountryId = $recipientOrgInfo->code;

            $recipientCountryCode = $this->connection->table('Country')
                                                     ->select('Code')
                                                     ->where('id', '=', $recipientCountryId)
                                                     ->first();

            $countryCode       = $recipientCountryCode ? $recipientCountryCode->Code : '';
            $countryPercentage = $recipientOrgInfo ? $recipientOrgInfo->percentage : '';

            $recipientCountryNarratives = $this->connection->table('iati_recipient_country/narrative')
                                                           ->select('*', '@xml_lang as xml_lang')
                                                           ->where('recipient_country_id', '=', $recipientOrgInfo->id)
                                                           ->get(); //Can be many
            $Narrative                  = [];

            foreach ($recipientCountryNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataRecipientCountry[] = $this->recipientCountry->format($countryCode, $countryPercentage, $Narrative, $recipientCountryNarratives);
        }

        if (!is_null($recipientOrgInstances)) {
            $this->data[$activityId]['recipient_country'] = $dataRecipientCountry;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchRecipientRegion($activityId)
    {
        $dataRecipientRegion      = null;
        $language                 = "";
        $recipientRegionInstances = $this->connection->table('iati_recipient_region')
                                                     ->select('*', '@code as code', '@percentage as percentage', '@vocabulary as vocabulary')
                                                     ->where('activity_id', '=', $activityId)
                                                     ->get();

        foreach ($recipientRegionInstances as $recipientRegionInfo) {
            $regionId           = $recipientRegionInfo->code;
            $regionVocabularyId = $recipientRegionInfo->vocabulary;
            $regionPercentage   = $recipientRegionInfo->percentage;

            $fetchRegionCode = $this->connection->table('Region')
                                                ->select('Code')
                                                ->where('id', '=', $regionId)
                                                ->first();

            $regionCode = $fetchRegionCode ? $fetchRegionCode->Code : '';

            $fetchRegionVocabularyCode = $this->connection->table('RegionVocabulary')
                                                          ->select('Code')
                                                          ->where('id', '=', $regionVocabularyId)
                                                          ->first();

            $regionVocabularyCode       = $fetchRegionVocabularyCode ? $fetchRegionVocabularyCode->Code : '';
            $recipientRegionId          = $recipientRegionInfo ? $recipientRegionInfo->id : '';
            $recipientCountryNarratives = $this->connection->table('iati_recipient_region/narrative')
                                                           ->select('*', '@xml_lang as xml_lang')
                                                           ->where('recipient_region_id', '=', $recipientRegionId)
                                                           ->get();

            $Narrative = [];

            foreach ($recipientCountryNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;
                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }
                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataRecipientRegion[] = $this->recipientRegion->format($regionCode, $regionVocabularyCode, $regionPercentage, $Narrative, $recipientCountryNarratives);
        }

        if (!is_null($recipientRegionInstances)) {
            $this->data[$activityId]['recipient_region'] = $dataRecipientRegion;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchSector($activityId)
    {
        $dataSector      = null;
        $language        = "";
        $sectorCode      = "";
        $sectorInstances = $this->connection->table('iati_sector')
                                            ->select('*', '@vocabulary as vocabulary', '@code as code', '@percentage as percentage')
                                            ->where('activity_id', '=', $activityId)
                                            ->get();

        foreach ($sectorInstances as $sectorInfo) {
            $sector_code  = $sector_category_code = $sector_text = "";  // initially null
            $vocabId      = $sectorInfo->vocabulary;
            $vocabCode    = fetchCode($vocabId, 'SectorVocabulary', $activityId);
            $sectorCodeId = $sectorInfo->code;
            $percentage   = $sectorInfo->percentage;

            if (!is_null($vocabId)) {
                $sectorCode = fetchCode($sectorCodeId, 'Sector', $activityId);
            }

            $sectorNarratives = fetchNarratives($sectorInfo->id, 'iati_sector/narrative', 'sector_id');
            $Narrative        = [];

            foreach ($sectorNarratives as $eachNarrative) {
                $narrativeText = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative[] = ['narrative' => $narrativeText, 'language' => $language];
            }

            $dataSector[] = $this->sector->format($vocabCode, $sector_code, $sector_category_code, $sector_text, $percentage, $Narrative, $sectorCode, $sectorCodeId, $sectorNarratives);
        }

        if (!is_null($sectorInstances)) {
            $this->data[$activityId]['sector'] = $dataSector;
        }

        return $this;
    }

    public function fetchContactInfo($activityId)
    {
        $typeCode             = $contactInfoDepartmentNarrative = $contactOrgNarrative = $contactInfoPersonName = $contactInfoPersonNarrative = $contactInfoJobNarrative = $contactInfoJobTitle = null;
        $contactInfoData      = null;
        $select               = ['id', '@type as type'];
        $contactInfoInstances = getBuilderFor($select, 'iati_contact_info', 'activity_id', $activityId)->get();

        foreach ($contactInfoInstances as $eachcontactInfo) {
            $typeCode = fetchCode($eachcontactInfo->type, 'ContactType', '');
            //Organisation
            $contactInfoOrganisation = getBuilderFor('*', 'iati_contact_info/organisation', 'contact_info_id', $eachcontactInfo->id)->first();
            if ($contactInfoOrganisation) {
                $contactInfoOrgNarrativeId = $contactInfoOrganisation->id;
                $contactInfoOrgNarratives  = fetchNarratives($contactInfoOrgNarrativeId, 'iati_contact_info/organisation/narrative', 'organisation_id');
                $contactOrgNarrative       = fetchAnyNarratives($contactInfoOrgNarratives);
            }
            //Department
            $contactInfoDepartment = getBuilderFor('*', 'iati_contact_info/department', 'contact_info_id', $eachcontactInfo->id)->first();
            if ($contactInfoDepartment) {
                $contactInfoDepartmentNarrativeId = $contactInfoDepartment->id;
                $contactInfoDepartmentNarratives  = fetchNarratives($contactInfoDepartmentNarrativeId, 'iati_contact_info/department/narrative', 'department_id');
                $contactInfoDepartmentNarrative   = fetchAnyNarratives($contactInfoDepartmentNarratives);
            }
            //Person Name
            $contactInfoPersonName = getBuilderFor('*', 'iati_contact_info/person_name', 'contact_info_id', $eachcontactInfo->id)->first();
            if ($contactInfoPersonName) {
                $personNameNarrativeId                 = $contactInfoPersonName->id;
                $contactInfoPersonNarrativesCollection = fetchNarratives($personNameNarrativeId, 'iati_contact_info/person_name/narrative', 'person_name_id');
                $contactInfoPersonNarrative            = fetchAnyNarratives($contactInfoPersonNarrativesCollection);
            }

            $telephone = $email = $website = $mailingAddress = null;
            //Job Title
            $contactInfoJobTitle = getBuilderFor('*', 'iati_contact_info/job_title', 'contact_info_id', $eachcontactInfo->id)->first();
            if ($contactInfoJobTitle) {
                $jobTitleNarrativeId                     = $contactInfoJobTitle->id;
                $contactInfoJobTitleNarrativesCollection = fetchNarratives($jobTitleNarrativeId, 'iati_contact_info/job_title/narrative', 'job_title_id');
                $contactInfoJobNarrative                 = fetchAnyNarratives($contactInfoJobTitleNarrativesCollection);
                $telephone                               = $email = $website = null;
            }
            //Telephone
            $contactInfoTelephones = getBuilderFor('text', 'iati_contact_info/telephone', 'contact_info_id', $eachcontactInfo->id)->get();
            foreach ($contactInfoTelephones as $phone) {
                if (!is_null($phone)) {
                    $telephone[] = ['telephone' => $phone->text];
                }
            }

            //Email
            $contactInfoEmails = getBuilderFor('*', 'iati_contact_info/email', 'contact_info_id', $eachcontactInfo->id)->get();
            foreach ($contactInfoEmails as $email_id) {
                if (!is_null($email_id)) {
                    $email[] = ['email' => $email_id->text];
                }
            }

            //Website
            $contactInfoWebsites = getBuilderFor('*', 'iati_contact_info/website', 'contact_info_id', $eachcontactInfo->id)->get();
            foreach ($contactInfoWebsites as $websites) {
                if (!is_null($websites)) {
                    $website[] = ['website' => $websites->text];
                }
            }

            //Mailing Address
            $contactInfoMailNarrativeBlocks = getBuilderFor('*', 'iati_contact_info/mailing_address', 'contact_info_id', $eachcontactInfo->id)->get();

            foreach ($contactInfoMailNarrativeBlocks as $blocks) {
                $narrativeBlockContent = fetchNarratives($blocks->id, 'iati_contact_info/mailing_address/narrative', 'mailing_address_id');
                $narratives            = fetchAnyNarratives($narrativeBlockContent);
                $mailingAddress[]      = ['narrative' => $narratives];
            }

            $contactInfo = new ContactInfo();

            $contactInfoData[] = $contactInfo->format(
                $typeCode,
                $contactOrgNarrative,
                $contactInfoDepartmentNarrative,
                $contactInfoPersonNarrative,
                $contactInfoJobNarrative,
                $telephone,
                $email,
                $website,
                $mailingAddress
            );

        }    // end of ContactInfoInstances

        if (!is_null($contactInfoInstances)) {
            $this->data[$activityId]['contact_info'] = $contactInfoData;
        }

        return $this;
    }

    public function fetchActivityScope($activityId)
    {
        $activityScope     = getBuilderFor('@code as code', 'iati_activity_scope', 'activity_id', $activityId)->first();
        $activityScopeData = null;

        if ($activityScope) {
            $activityScopeId   = $activityScope->code;
            $activityScopeCode = getBuilderFor('Code', 'ActivityScope', 'id', $activityScopeId)->first();
            $activityScopeData = $activityScopeCode->Code;
        }

        $this->data[$activityId]['activity_scope'] = $activityScopeData;

        return $this;
    }

    public function fetchLocation($activityId)
    {
        $locationData     = null;
        $select           = ['id', '@ref as ref'];
        $locationInstance = getBuilderFor($select, 'iati_location', 'activity_id', $activityId)->get();

        $ref                        = null;
        $locationReach              = null;
        $locationId                 = null;
        $fetchNameNarratives        = null;
        $fetchDescriptionNarratives = null;
        $fetchActivityNarratives    = null;
        $administrativeData         = null;
        $srsName                    = null;
        $exactnessCode              = null;
        $locationClassCode          = null;
        $featureDesignationCode     = null;
        $locationIdVocabulary       = '';
        $positionData               = ['latitude' => '', 'longitude' => ''];

        foreach ($locationInstance as $location) {
            $ref = $location->ref;

            //location Reach
            $locationReachId = getBuilderFor('@code as code', 'iati_location/location_reach', 'location_id', $location->id)->first();
            if (($locationReachId)) {
                $locationReachInstance = getBuilderFor('Code', 'GeographicLocationReach', 'id', $locationReachId->code)->first();
                $locationReach         = $locationReachInstance->Code;
            }
            //location Id
            $select             = ['@code as code', '@vocabulary as vocabulary'];
            $locationIdInstance = getBuilderFor($select, 'iati_location/location_id', 'location_id', $location->id)->get();

            if ($locationIdInstance) {
                foreach ($locationIdInstance as $eachLocationId) {
                    $locationIdVocab = getBuilderFor('Code', 'GeographicVocabulary', 'id', $eachLocationId->vocabulary)->first();

                    if ($locationIdVocab) {
                        $locationIdVocabulary = $locationIdVocab->Code;
                    }
                    $locationIdCode = $eachLocationId->code;
                    $locationId[]   = ['vocabulary' => $locationIdVocabulary, 'code' => $locationIdCode];
                }

            }
            // name
            $locationNameId = getBuilderFor('id', 'iati_location/name', 'location_id', $location->id)->first();
            if ($locationNameId) {
                $locationNameInstance = fetchNarratives($locationNameId->id, 'iati_location/name/narrative', 'name_id');
                $fetchNameNarratives  = fetchAnyNarratives($locationNameInstance);
            }

            //description
            $locationDescriptionId = getBuilderFor('id', 'iati_location/description', 'location_id', $location->id)->first();

            if ($locationDescriptionId) {
                $locationDescriptionInstance = fetchNarratives($locationDescriptionId->id, 'iati_location/description/narrative', 'description_id');
                $fetchDescriptionNarratives  = fetchAnyNarratives($locationDescriptionInstance);
            }

            //activity description
            $activityDescriptionId = getBuilderFor('id', 'iati_location/activity_description', 'location_id', $location->id)->first();

            if ($activityDescriptionId) {
                $activityDescriptionInstance = fetchNarratives($activityDescriptionId->id, 'iati_location/activity_description/narrative', 'activity_description_id');
                $fetchActivityNarratives     = fetchAnyNarratives($activityDescriptionInstance);
            }

            $select             = ['@code as code', '@level as level', '@vocabulary as vocabulary'];
            $administrativeInfo = getBuilderFor($select, 'iati_location/administrative', 'location_id', $location->id)->get();

            if ($administrativeInfo) {
                foreach ($administrativeInfo as $administrative) {
                    $vocabularyCode       = fetchCode($administrative->vocabulary, 'GeographicVocabulary', '');
                    $administrativeData[] = ['vocabulary' => $vocabularyCode, 'code' => $administrative->code, 'level' => $administrative->level];
                }
            }

            //point
            $select    = ['@srsName as srsName', 'id', 'location_id'];
            $pointInfo = getBuilderFor($select, 'iati_location/point', 'location_id', $location->id)->first();

            if ($pointInfo) {
                $srsName      = $pointInfo->srsName;
                $select       = ['@latitude as latitude', '@longitude as longitude'];
                $positionInfo = getBuilderFor($select, 'iati_location/point/pos', 'point_id', $pointInfo->id)->first();

                $positionData = ['latitude' => ($positionInfo) ? $positionInfo->latitude : '', 'longitude' => ($positionInfo) ? $positionInfo->longitude : ''];
                $pointData    = ['srs_name' => $srsName, 'position' => [$positionData]];
            }
            $exactnessInfo = getBuilderFor('@code as code', 'iati_location/exactness', 'location_id', $location->id)->first();

            if ($exactnessInfo) {
                $exactnessCode = fetchCode($exactnessInfo->code, 'GeographicExactness', '');
            }

            $locationClass = getBuilderFor('@code as code', 'iati_location/location_class', 'location_id', $location->id)->first();
            if ($locationClass) {
                $locationClassCode = fetchCode($locationClass->code, 'GeographicLocationClass', '');
            }

            $featureDesignation = getBuilderFor('@code as code', 'iati_location/feature_designation', 'location_id', $location->id)->first();
            if ($featureDesignation) {
                $featureDesignationCode = fetchCode($featureDesignation->code, 'LocationType', '');
            }

            $locationFormatter = new Location();
            $locationData[]    = $locationFormatter->format(
                $ref,
                $locationReach,
                $locationId,
                $fetchNameNarratives,
                $fetchDescriptionNarratives,
                $fetchActivityNarratives,
                $administrativeData,
                $srsName,
                $exactnessCode,
                $locationClassCode,
                $featureDesignationCode,
                $positionData
            );
        } // end of locationInstances

        if (!is_null($locationInstance)) {
            $this->data[$activityId]['location'] = $locationData;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchPolicyMarker($activityId)
    {
        $select           = ['*', '@code as code', '@vocabulary as vocabulary', '@significance as significance'];
        $policyMarkers    = getBuilderFor($select, 'iati_policy_marker', 'activity_id', $activityId)->get();
        $policyMarkerData = null;

        foreach ($policyMarkers as $policyMarker) {
            $policyMarkerNarrative = $this->fetchPolicyMarkerNarrative($policyMarker);
            $policyMarkerFormatter = new PolicyMarker();
            $policyMarkerData[]    = $policyMarkerFormatter->format($policyMarker, $policyMarkerNarrative);
        }

        $this->data[$activityId]['policy_marker'] = $policyMarkerData;

        return $this;
    }

    /**
     * @param $policyMarker
     * @return array
     */
    protected function fetchPolicyMarkerNarrative($policyMarker)
    {
        $policyMarkerNarrative = fetchNarratives($policyMarker->id, 'iati_policy_marker/narrative', 'policy_marker_id');

        return fetchAnyNarratives($policyMarkerNarrative);
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchCollaborationType($activityId)
    {
        $collaborationType      = getBuilderFor('@code as code', 'iati_collaboration_type', 'activity_id', $activityId)->first();
        $collaborationTypeValue = null;

        if ($collaborationType) {
            $collaborationTypeValue = fetchCode($collaborationType->code, 'CollaborationType');
        }

        $this->data[$activityId]['collaboration_type'] = $collaborationTypeValue;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchDefaultFlowType($activityId)
    {
        $defaultFlowType      = getBuilderFor('@code as code', 'iati_default_flow_type', 'activity_id', $activityId)->first();
        $defaultFlowTypeValue = null;

        if ($defaultFlowType) {
            $defaultFlowTypeValue = fetchCode($defaultFlowType->code, 'FlowType');
        }
        $this->data[$activityId]['default_flow_type'] = $defaultFlowTypeValue;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchDefaultFinanceType($activityId)
    {
        $defaultFinanceType      = getBuilderFor('@code as code', 'iati_default_finance_type', 'activity_id', $activityId)->first();
        $defaultFinanceTypeValue = null;

        if ($defaultFinanceType) {
            $defaultFinanceTypeValue = fetchCode($defaultFinanceType->code, 'FinanceType');
        }
        $this->data[$activityId]['default_finance_type'] = $defaultFinanceTypeValue;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchDefaultAidType($activityId)
    {
        $defaultAidType      = getBuilderFor('@code as code', 'iati_default_aid_type', 'activity_id', $activityId)->first();
        $defaultAidTypeValue = null;

        if ($defaultAidType) {
            $defaultAidTypeValue = fetchCode($defaultAidType->code, 'AidType');
        }
        $this->data[$activityId]['default_aid_type'] = $defaultAidTypeValue;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchDefaultTiedStatus($activityId)
    {
        $defaultTiedStatus      = getBuilderFor('@code as code', 'iati_default_tied_status', 'activity_id', $activityId)->first();
        $defaultTiedStatusValue = null;

        if ($defaultTiedStatus) {
            $defaultTiedStatusValue = fetchCode($defaultTiedStatus->code, 'TiedStatus');
        }
        $this->data[$activityId]['default_tied_status'] = $defaultTiedStatusValue;

        return $this;
    }

    public function fetchCountryBudgetItems($activityId)
    {
        $countryBudgetItemsData = [];
        $description            = [];
        $budgetItemsArray       = [];
        $vocabularyCode         = '';
        $select                 = ['id', '@vocabulary as vocabulary'];
        $budgetItemInstance     = getBuilderFor($select, 'iati_country_budget_items', 'activity_id', $activityId)->first();
        $budgetItemsArray       = [];
        $description            = [];

        if ($budgetItemInstance) {
            $vocabularyCode = fetchCode($budgetItemInstance->vocabulary, 'BudgetIdentifierVocabulary', '');

            $select           = ['@code as code', '@percentage as percentage', 'id'];
            $budgetItemsBlock = getBuilderFor($select, 'iati_country_budget_items/budget_item', 'country_budget_items_id', $budgetItemInstance->id)->get();

            foreach ($budgetItemsBlock as $budgetItems) {
                $budgetCode       = fetchCode($budgetItems->code, 'BudgetIdentifier');
                $budgetPercentage = $budgetItems->percentage;

                //Description
                $DescriptionInstance = getbuilderFor('id', 'iati_country_budget_items/budget_item/description', 'budget_item_id', $budgetItems->id)->get();

                foreach ($DescriptionInstance as $eachDescription) {
                    $fetchDescriptionNarratives = fetchNarratives($eachDescription->id, 'iati_country_budget_items/budget_item/description/narrative', 'description_id');
                    $descriptionNarratives      = fetchAnyNarratives($fetchDescriptionNarratives);

                    $description[] = ['narrative' => $descriptionNarratives];
                }

                if ($vocabularyCode == 1) {
                    $budgetItemsArray[] = ['code' => $budgetCode, 'percentage' => $budgetPercentage, 'description' => $description, 'code_text' => ''];
                } else {
                    $budgetItemsArray[] = ['code_text' => $budgetCode, 'percentage' => $budgetPercentage, 'description' => $description, 'code' => ''];
                }
            }

            $countryBudgetItem        = new CountryBudgetItem();
            $countryBudgetItemsData[] = $countryBudgetItem->format($vocabularyCode, $budgetItemsArray);
        }

        if (!is_null($budgetItemInstance)) {
            $this->data[$activityId]['country_budget_items'] = $countryBudgetItemsData;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    public function fetchLegacyData($activityId)
    {
        $select             = ['@name as name', '@value as value', '@iati_equivalent as iati_equivalent'];
        $legacyDataInstance = getBuilderFor($select, 'iati_legacy_data', 'activity_id', $activityId)->get();
        $legacyData         = null;

        foreach ($legacyDataInstance as $eachLegacyData) {
            $legacyDataFormatter = new LegacyData();
            $legacyData[]        = $legacyDataFormatter->format($eachLegacyData);
        }

        if (!is_null($legacyDataInstance)) {
            $this->data[$activityId]['legacy_data'] = $legacyData;
        }

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchCapitalSpend($activityId)
    {
        $capitalSpend      = getBuilderFor('@percentage as percentage', 'iati_capital_spend', 'activity_id', $activityId)->first();
        $capitalSpendValue = null;

        if ($capitalSpend) {
            $capitalSpendValue = $capitalSpend->percentage;
        }
        $this->data[$activityId]['capital_spend'] = $capitalSpendValue;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchPlannedDisbursement($activityId)
    {
        $select                  = ['id', '@type as type'];
        $plannedDisbursements    = getBuilderFor($select, 'iati_planned_disbursement', 'activity_id', $activityId)->get();
        $plannedDisbursementData = [];

        foreach ($plannedDisbursements as $plannedDisbursement) {
            $plannedDisbursementFormatter = new PlannedDisbursement();
            $plannedDisbursementData[]    = $plannedDisbursementFormatter->format($plannedDisbursement);
        }

        $this->data[$activityId]['planned_disbursement'] = $plannedDisbursementData;

        return $this;
    }

    /**
     * @param $activityId
     * @return $this
     */
    protected function fetchRelatedActivity($activityId)
    {
        $select              = ['@type as type', '@ref as text'];
        $relatedActivities   = getBuilderFor($select, 'iati_related_activity', 'activity_id', $activityId)->get();
        $relatedActivityData = [];

        foreach ($relatedActivities as $relatedActivity) {
            $relatedActivityFormatter = new RelatedActivity();
            $relatedActivityData[]    = $relatedActivityFormatter->format($relatedActivity);
        }
        $this->data[$activityId]['related_activity'] = $relatedActivityData;

        return $this;
    }

    protected function fetchDocumentLink($activityId)
    {
        $select           = ['*', '@url as url', '@format as format'];
        $documentLinks    = getBuilderFor($select, 'iati_document_link', 'activity_id', $activityId)->get();
        $documentLinkData = [];

        foreach ($documentLinks as $documentLink) {
            $fileFormat = ($documentLink->format) ? (fetchCode($documentLink->format, 'FileFormat')) : '';
            $title      = $this->fetchDocumentLinkTitle($documentLink);
            $category   = $this->fetchDocumentLinkCategory($documentLink);
            $language   = $this->fetchDocumentLinkLanguage($documentLink);

            $documentLinkFormatter = new ActivityDocumentLink();
            $documentLinkData[]    = $documentLinkFormatter->format($documentLink, $fileFormat, $title, $category, $language);
        }
        $this->data[$activityId]['document_link'] = $documentLinkData;

        return $this;
    }

    protected function fetchBudgetData($activityId)
    {
        $select     = ['id', '@type as type'];
        $budgets    = getBuilderFor($select, 'iati_budget', 'activity_id', $activityId)->get();
        $budgetData = [];

        foreach ($budgets as $budget) {
            $budgetFormatter = new Budget();
            $budgetData []   = $budgetFormatter->format($budget);
        }
        $this->data[$activityId]['budget'] = $budgetData;

        return $this;
    }

    public function fetchConditions($activityId)
    {
        $conditionInfo  = null;
        $select         = ['@attached as attached', 'id'];
        $iatiConditions = getBuilderFor($select, 'iati_conditions', 'activity_id', $activityId)->first();

        $condition = [];

        if ($iatiConditions) {
            $attached          = $iatiConditions->attached;
            $select            = ['id', '@type as type', 'conditions_id'];
            $conditionInstance = getBuilderFor($select, 'iati_conditions/condition', 'conditions_id', $iatiConditions->id)->get();

            foreach ($conditionInstance as $eachCondition) {
                $typeCode = fetchCode($eachCondition->type, 'ConditionType');

                $fetchNarratives = fetchNarratives($eachCondition->id, 'iati_conditions/condition/narrative', 'condition_id');
                $narratives      = fetchAnyNarratives($fetchNarratives);
                $condition[]     = ['condition_type' => $typeCode, 'narrative' => $narratives];
            }

            $conditionFormat = new Condition();
            $conditionInfo   = $conditionFormat->format($attached, $condition);
        }

        if (!is_null($iatiConditions)) {
            $this->data[$activityId]['conditions'] = $conditionInfo;
        }

        return $this;
    }

    /**
     * @param $documentLink
     * @return array
     */
    protected function fetchDocumentLinkTitle($documentLink)
    {
        $documentLinkTitle = getBuilderFor('*', 'iati_document_link/title', 'document_link_id', $documentLink->id)->first();
        $narrative         = '';
        if ($documentLinkTitle) {
            $narrativeData = fetchNarratives($documentLinkTitle->id, 'iati_document_link/title/narrative', 'title_id');
            $narrative     = fetchAnyNarratives($narrativeData);
        }

        $documentLinkTitleData = [
            'narrative' => $narrative
        ];

        return $documentLinkTitleData;
    }

    /**
     * @param $documentLink
     * @return array
     */
    protected function fetchDocumentLinkCategory($documentLink)
    {
        $select                 = ['@code as code'];
        $documentLinkCategories = getBuilderFor($select, 'iati_document_link/category', 'document_link_id', $documentLink->id)->get();
        $categoryCode           = [];

        foreach ($documentLinkCategories as $documentLinkCategory) {
            $documentLinkCategoryCode = ($documentLinkCategory->code) ? (fetchCode($documentLinkCategory->code, 'DocumentCategory')) : '';

            $categoryCode[] = [
                'code' => $documentLinkCategoryCode
            ];
        }

        return $categoryCode;
    }

    /**
     * @param $documentLink
     * @return array
     */
    protected function fetchDocumentLinkLanguage($documentLink)
    {
        $select                   = ['@code as code'];
        $documentLinkLanguages    = getBuilderFor($select, 'iati_document_link/language', 'document_link_id', $documentLink->id)->get();
        $documentLinkLanguageData = [];

        foreach ($documentLinkLanguages as $documentLinkLanguage) {
            $documentLinkLanguageCode = ($documentLinkLanguage->code) ? (getLanguageCodeFor($documentLinkLanguage->code)) : '';

            $documentLinkLanguageData[] = [
                'language' => $documentLinkLanguageCode
            ];
        }

        return $documentLinkLanguageData;
    }

    /**
     * @param $activity
     * @return $this
     */
    public function fetchDefaultFieldValues($activity)
    {
        $defaultFieldValues                                = [
            [
                "linked_data_uri"            => $activity->linked_data_uri,
                "default_language"           => getLanguageCodeFor($activity->xml_lang),
                "default_currency"           => fetchCode($activity->default_currency, 'Currency'),
                "default_hierarchy"          => $activity->hierarchy,
                "default_collaboration_type" => "",
                "default_flow_type"          => "",
                "default_finance_type"       => "",
                "default_aid_type"           => "",
                "default_tied_status"        => ""
            ]
        ];
        $this->data[$activity->id]['default_field_values'] = $defaultFieldValues;

        return $this;
    }
}
