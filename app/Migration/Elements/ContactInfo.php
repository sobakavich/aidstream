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
            'organization'    => [['narrative' => isset($contactOrgNarrative) ? $contactOrgNarrative : [['narrative' => "", 'language' => ""]]]],
            'department'      => [['narrative' => isset($contactInfoDepartmentNarrative) ? $contactInfoDepartmentNarrative : [['narrative' => "", 'language' => ""]]]],
            'person_name'     => [['narrative' => isset($contactInfoPersonNarrative) ? $contactInfoPersonNarrative : [['narrative' => "", 'language' => ""]]]],
            'job_title'       => [['narrative' => isset($contactInfoJobNarrative) ? $contactInfoJobNarrative : [['narrative' => "", 'language' => ""]]]],
            'telephone'       => $telephone ? $telephone : [['telephone' => '']],
            'email'           => $email ? $email : [['email' => '']],
            'website'         => $website ? $website : [['website' => '']],
            'mailing_address' => $mailingAddress ? $mailingAddress : ['narrative' => ['narrative' => "", 'language' => ""]]
        ];

        return $contactInfoData;
    }
}
