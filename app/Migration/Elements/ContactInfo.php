<?php namespace App\Migration\Elements;


class ContactInfo
{
    public function format(
        $typeCode,
        $contactOrgNarrative,
        $contactInfoDepartmentNarrative,
        $contactInfoPersonNarrative,
        $contactInfoJobNarrative,
        $telephone,
        $email,
        $website,
        $mailingAddress
    ) {
        $contactInfoData = [
            'type'            => $typeCode,
            'organization'    => [['narrative' => isset($contactOrgNarrative) ? $contactOrgNarrative : []]],
            'department'      => [['narrative' => isset($contactInfoDepartmentNarrative) ? $contactInfoDepartmentNarrative : []]],
            'person_name'     => [['narrative' => isset($contactInfoPersonNarrative) ? $contactInfoPersonNarrative : []]],
            'job_title'       => [['narrative' => isset($contactInfoJobNarrative) ? $contactInfoJobNarrative : []]],
            'telephone'       => $telephone ? $telephone : [],
            'email'           => $email ? $email : [],
            'website'         => $website ? $website : [],
            'mailing_address' => $mailingAddress ? $mailingAddress : []
        ];

        return $contactInfoData;
    }
}
