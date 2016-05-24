<?php namespace App\Tz\Aidstream\Traits;

use App\Tz\Aidstream\Models\Project;

/**
 * Class FormatsProjectFormInformation
 * @package App\Tz\Aidstream\Traits
 */
trait FormatsProjectFormInformation
{
    /**
     * @var array
     */
    protected $identifier = ['identifier' => ['activity_identifier' => '', 'iati_identifier_text' => '']];

    /**
     * @var array
     */
    protected $otherIdentifier = [
        'other_identifier' => [
            [
                'reference' => '',
                'type'      => '',
                'owner_org' => [['reference' => '', 'narrative' => [['narrative' => '', 'language' => '']]]]
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $title = ['title' => [['narrative' => '', 'language' => '']]];

    /**
     * @var array
     */
    protected $description = [
        'description' => [
            [
                'type'      => '',
                'narrative' => [
                    [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $activityStatus = ['activity_status' => 0];

    /**
     * @var array
     */
    protected $activityDate = [
        'activity_date' => [
            [
                "date"      => "",
                "type"      => "",
                "narrative" => [
                    ["narrative" => "", "language" => ""]
                ]
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $participatingOrg = [
        [
            "organization_role" => "",
            "identifier"        => "",
            "organization_type" => "",
            "narrative"         => [["narrative" => "", "language" => ""]]
        ]
    ];

    /**
     * @var array
     */
    protected $sectors = [
        [
            "sector_vocabulary"    => "",
            "sector_code"          => "",
            "sector_category_code" => "",
            "sector_text"          => "",
            "percentage"           => "",
            "narrative"            => [["narrative" => "", "language" => ""]],
            "vocabulary_uri"       => ""
        ]
    ];

    /**
     * @var array
     */
    protected $country = [["country_code" => "", "percentage" => "", "narrative" => [["narrative" => "", "language" => ""]]]];

    /**
     * @var array
     */
    protected $location = [
        [
            "reference"            => "",
            "location_reach"       => [["code" => ""]],
            "location_id"          => [["vocabulary" => "", "code" => ""]],
            "name"                 => [["narrative" => []]],
            "location_description" => [["narrative" => []]],
            "activity_description" => [["narrative" => []]],
            "administrative"       => [["vocabulary" => "", "code" => "", "level" => ""]],
            "point"                => [["srs_name" => "", "position" => [["latitude" => "", "longitude" => ""]]]],
            "exactness"            => [["code" => ""]],
            "location_class"       => [["code" => ""]],
            "feature_designation"  => [["code" => ""]]
        ]
    ];

    /**
     * @var array
     */
    protected $region = [["region_code" => "", "region_vocabulary" => "", "percentage" => "", "narrative" => [["narrative" => "", "language" => ""]]]];


//
    /**
     * @var array
     */
    protected $template = [
        'identifier'       => ['activity_identifier' => '', 'iati_identifier_text' => ''],
        'other_identifier' => [
            [
                'reference' => '',
                'type'      => '',
                'owner_org' => [['reference' => '', 'narrative' => [['narrative' => '', 'language' => '']]]]
            ]
        ],
        'title'            => [['narrative' => '', 'language' => '']],
        'description'      => [
            [
                'type'      => '',
                'narrative' => [
                    [
                        'narrative' => '',
                        'language'  => ''
                    ]
                ]
            ]
        ],
        'activity_status'  => 0,
        'activity_date'    => [
            [
                "date"      => "",
                "type"      => "",
                "narrative" => [
                    ["narrative" => "", "language" => ""]
                ]
            ]
        ],
        'contact_info'     => [
            [
                "type"            => "",
                "organization"    => [["narrative" => [["narrative" => "", "language" => ""]]]],
                "department"      => [["narrative" => [["narrative" => "", "language" => ""]]]],
                "person_name"     => [["narrative" => [["narrative" => "", "language" => ""]]]],
                "job_title"       => [["narrative" => [["narrative" => "", "language" => ""]]]],
                "telephone"       => [["telephone" => ""]],
                "email"           => [["email" => ""]],
                "website"         => [["website" => ""]],
                "mailing_address" => [["narrative" => [["narrative" => "", "language" => ""]]]]
            ]
        ]
    ];

    /**
     * @param $projectDetails
     * @return array
     */
    public function process($projectDetails)
    {
        if ($projectDetails instanceof Project) {
            $details['identifier'] = $projectDetails->identifier['activity_identifier'];
            $details['title']      = $projectDetails->title[0]['narrative'];

            //            $details['identifier'] = $projectDetails->identifier['activity_identifier'];

            return $projectDetails;
        }

        $details                                       = $this->template;
        $details['identifier']['iati_identifier_text'] = $projectDetails['identifier'];
        $details['title'][0]['narrative']              = $projectDetails['title'];
        //        $details['title'][0]['language']              = defaultLanguage;
        $details['organization_id'] = $projectDetails['organization_id'];

        return $details;
    }

    public function processDefaultFieldValues($projectDetails)
    {
        return [
            'default_field_values' => [
                [
                    'default_currency' => $projectDetails['default_currency'],
                    'default_language' => $projectDetails['default_language']
                ]
            ]
        ];
    }
}
