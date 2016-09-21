<?php namespace App\Http\Controllers\TestData\Traits;


trait BasicActivityData
{
    protected function getIdentifier($activity)
    {
        $this->dataContainer[$this->index]['activity_identifier'] = getVal((array) $activity->identifier, ['activity_identifier']);
    }

    protected function getTitle($activity)
    {
        $this->dataContainer[$this->index]['activity_title'] = getVal((array) $activity->title, [0, 'narrative']);
    }

    protected function getGeneralDescription($activity)
    {
        $descriptionType = getVal((array) $activity->description, [0, 'type']);

        $this->dataContainer[$this->index]['activity_description_general'] = ($descriptionType == 1) ? getVal((array) $activity->description, [0, 'narrative', 0, 'narrative']) : '';
    }

    protected function getObjectivesDescription($activity)
    {
        $descriptionType = getVal((array) $activity->description, [0, 'type']);

        $this->dataContainer[$this->index]['activity_description_objectives'] = ($descriptionType == 2) ? getVal((array) $activity->description, [0, 'narrative', 0, 'narrative']) : '';
    }

    protected function getTargetGroupsDescription($activity)
    {
        $descriptionType = getVal((array) $activity->description, [0, 'type']);

        $this->dataContainer[$this->index]['activity_description_target_groups'] = ($descriptionType == 3) ? getVal((array) $activity->description, [0, 'narrative', 0, 'narrative']) : '';
    }

    protected function getOthersDescription($activity)
    {
        $descriptionType = getVal((array) $activity->description, [0, 'type']);

        $this->dataContainer[$this->index]['activity_description_others'] = ($descriptionType == 4) ? getVal((array) $activity->description, [0, 'narrative', 0, 'narrative']) : '';
    }

    protected function getActivityStatus($activity)
    {
        $this->dataContainer[$this->index]['activity_status'] = ($status = $activity->activity_status) ? $status : '';
    }

    protected function getActualStartDate($activity)
    {
        $dateType = getVal((array) $activity->activity_date, [0, 'type']);

        $this->dataContainer[$this->index]['actual_start_date'] = ($dateType == 2) ? getVal((array) $activity->activity_date, [0, 'date']) : '';
    }

    protected function getActualEndDate($activity)
    {
        $dateType = getVal((array) $activity->activity_date, [0, 'type']);

        $this->dataContainer[$this->index]['actual_end_date'] = ($dateType == 4) ? getVal((array) $activity->activity_date, [0, 'date']) : '';
    }

    protected function getPlannedStartDate($activity)
    {
        $dateType = getVal((array) $activity->activity_date, [0, 'type']);

        $this->dataContainer[$this->index]['planned_start_date'] = ($dateType == 1) ? getVal((array) $activity->activity_date, [0, 'date']) : '';
    }

    protected function getPlannedEndDate($activity)
    {
        $dateType = getVal((array) $activity->activity_date, [0, 'type']);

        $this->dataContainer[$this->index]['planned_end_date'] = ($dateType == 3) ? getVal((array) $activity->activity_date, [0, 'date']) : '';
    }

    protected function getParticipatingOrganisation($activity)
    {
        $this->rowIndexCount = 0;
        foreach ((array) $activity->participating_organization as $org) {
            $this->dataContainer[$this->index]['participating_organisation_role']       = $org['organization_role'];
            $this->dataContainer[$this->index]['participating_organisation_type']       = $org['organization_type'];
            $this->dataContainer[$this->index]['participating_organisation_name']       = getVal($org, ['narrative', 0, 'narrative']);
            $this->dataContainer[$this->index]['participating_organisation_identifier'] = getVal($org, ['identifier']);
            $this->index ++;
            $this->rowIndexCount ++;
        }
        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    protected function getRecipientCountry($activity)
    {
        $this->rowIndexCount = 0;
        foreach ((array) $activity->recipient_country as $country) {
            $this->dataContainer[$this->index]['recipient_country_code']       = $country['country_code'];
            $this->dataContainer[$this->index]['recipient_country_percentage'] = $country['percentage'];
            $this->index ++;
            $this->rowIndexCount ++;
        }
        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    protected function getRecipientRegion($activity)
    {
        $this->rowIndexCount = 0;
        foreach ((array) $activity->recipient_region as $region) {
            $this->dataContainer[$this->index]['recipient_region_code']       = $region['region_code'];
            $this->dataContainer[$this->index]['recipient_region_percentage'] = $region['percentage'];
            $this->index ++;
            $this->rowIndexCount ++;
        }
        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }

    protected function getSector($activity)
    {
        $this->rowIndexCount = 0;
        foreach ((array) $activity->sector as $sector) {
            $this->dataContainer[$this->index]['sector_vocabulary'] = $sector['sector_vocabulary'];
            $this->dataContainer[$this->index]['sector_code']       = $sector['sector_code'];
            $this->dataContainer[$this->index]['sector_percentage'] = $sector['percentage'];

            $this->index ++;
            $this->rowIndexCount ++;
        }
        $this->maxRowIndex = ($this->rowIndexCount > $this->maxRowIndex) ? $this->rowIndexCount : $this->maxRowIndex;
        $this->index       = $this->index - $this->rowIndexCount;
    }
}