<?php

namespace App\Console\Commands;

use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Organization\Organization;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ReportingOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:reporting-organizations {method} {pageNo=1} {--verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate registration agency and registration number from reporting organization identifier and add organization name to reporting org narrative';
    /**
     * @var Organization
     */
    protected $organization;
    protected $orgRepository;

    /**
     * Create a new command instance.
     *
     * @param Organization           $organization
     * @param OrganizationRepository $orgRepository
     */
    public function __construct(Organization $organization, OrganizationRepository $orgRepository)
    {
        parent::__construct();
        $this->organization  = $organization;
        $this->orgRepository = $orgRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $method = $this->argument('method');

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    protected function regInfoExcel()
    {
        Excel::create(
            'regInfo',
            function ($excel) {

                $excel->sheet(
                    'regInfo',
                    function ($sheet) {

                        $sheet->fromArray($this->regInfoData());

                    }
                );

            }
        )->store('xls');

        $this->info('Registration Info Excel has been generated.');
    }

    protected function regInfoData()
    {
        $organizations = $this->organization->all();
        $orgInfo       = [];

        foreach ($organizations as $organization) {
            if ($organization->reporting_org) {
                $orgIdentifier         = getVal($organization->reporting_org, [0, 'reporting_organization_identifier']);
                $registrationSeparator = strrpos($orgIdentifier, '-');

                if ($registrationSeparator) {
                    $registrationNumber = substr($orgIdentifier, $registrationSeparator + 1);
                } else {
                    $registrationNumber = substr($orgIdentifier, $registrationSeparator);
                }

                $registrationAgency = substr($orgIdentifier, 0, $registrationSeparator);
                $country            = substr($orgIdentifier, 0, strpos($orgIdentifier, '-'));
                $dbCountry          = $organization->country;


                $orgInfo[] = [
                    'Org ID'         => $organization->id,
                    'Org Name'       => $organization->name,
                    'Org Identifier' => $orgIdentifier,
                    'Reg Number'     => $registrationNumber,
                    'Reg Agency'     => $registrationAgency,
                    'Country'        => $country,
                    'DB Country'     => $dbCountry
                ];
            }
        }

        return $orgInfo;
    }

    protected function orgNameExcel()
    {
        Excel::create(
            'orgName',
            function ($excel) {

                $excel->sheet(
                    'orgName',
                    function ($sheet) {

                        $sheet->fromArray($this->orgNameData());

                    }
                );

            }
        )->store('xls');

        $this->info('Organization Name Excel has been generated.');
    }

    protected function orgNameData()
    {
        $organizations = $this->organization->all();
        $orgInfo       = [];
        foreach ($organizations as $organization) {
            $orgName = [];
            if ($organization->reporting_org != null) {
                $orgNarratives = (array) getVal($organization->reporting_org, [0, 'narrative']);

                foreach ($orgNarratives as $orgNarrative) {
                    $orgName[] = getVal($orgNarrative, ['narrative']);
                }
            }

            $orgInfo[] = [
                'Org ID'             => $organization->id,
                'Org Name'           => $organization->name,
                'Reporting Org Name' => implode(' **** ', $orgName)
            ];
        }

        return $orgInfo;
    }

    protected function regInfo()
    {
        $organizations = $this->organization->all();
        foreach ($organizations as $organization) {
            $orgIdentifier = "";
            if ($organization->reporting_org != null) {
                $orgIdentifier                = getVal($organization->reporting_org, [0, 'reporting_organization_identifier']);
                $organizationIdentifierPieces = explode('-', $orgIdentifier);
            }

            if ($organization->reporting_org != null && count($organizationIdentifierPieces) >= 3) {
                $country            = $organizationIdentifierPieces[0];
                $registrationAgency = $organizationIdentifierPieces[1];
                $registrationNumber = "";

                for ($i = 2; $i < count($organizationIdentifierPieces); $i ++) {
                    if ($i != 2) {
                        $registrationNumber .= '-';
                    }
                    $registrationNumber .= $organizationIdentifierPieces[$i];
                }
                $this->orgRepository->saveRegistrationInfo($organization->id, $registrationNumber, $registrationAgency, $country);
            } else {
                $this->orgRepository->saveRegistrationNo($organization->id, $orgIdentifier);
            }


        }
    }

    protected function orgName()
    {
        $organizations                = $this->organization->all();
        $organizationsWithDescription = [649, 655, 667, 692, 662, 701, 714];

        foreach ($organizations as $organization) {
            if ($organization->reporting_org) {
                $orgNarrative = getVal($organization->reporting_org, [0, 'narrative'], []);

                if ($orgNarrative == []) {
                    $orgName = [
                        "narrative" => $organization->name,
                        "language"  => ""
                    ];
                    $this->orgRepository->insertNarrativeBlock($organization->id, $orgName);
                } else {
                    ($organization->reporting_org[0]['narrative'][0]['narrative']) ?: $this->orgRepository->insertOrgName($organization->id, $organization->name);
                }
            } else {
                $organization->reporting_org = [
                    [
                        "reporting_organization_identifier" => "",
                        "reporting_organization_type"       => "",
                        "narrative"                         => [["narrative" => $organization->name, "language" => ""]]
                    ]
                ];

                $organization->save();
            }

            $this->saveOrganizationNameInPlaceOfDescription($organization, $organizationsWithDescription);
        }
    }

    protected function saveOrganizationNameInPlaceOfDescription($organization, $organizationsWithDescription)
    {
        if (in_array($organization->id, $organizationsWithDescription)) {
            $reportingOrganization                                 = $organization->reporting_org;
            $reportingOrganization[0]['narrative'][0]['narrative'] = $organization->name;
            $organization->reporting_org                           = $reportingOrganization;

            $organization->save();
        }
    }
}
