<?php

namespace App\Console\Commands;

use App\Models\Organization\Organization;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ReportingOrgs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:reportingOrgs {method} {pageNo=1} {--verify}';

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

    /**
     * Create a new command instance.
     *
     * @param Organization $organization
     */
    public function __construct(Organization $organization)
    {
        parent::__construct();
        $this->organization = $organization;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $method = $this->argument('method');
        $this->$method();
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
            $orgIdentifier         = $organization->reporting_org[0]['reporting_organization_identifier'];
            $registrationSeparator = strrpos($orgIdentifier, '-');
            $registrationNumber    = substr($orgIdentifier, $registrationSeparator + 1);
            $registrationAgency    = substr($orgIdentifier, 0, $registrationSeparator);
            $country               = substr($orgIdentifier, 0, strpos($orgIdentifier, '-'));
            $dbCountry             = $organization->country;


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
            $orgNarratives = (array) $organization->reporting_org[0]['narrative'];
            $orgName       = [];

            foreach ($orgNarratives as $orgNarrative) {
                $orgName[] = $orgNarrative['narrative'];
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
            $orgIdentifier         = $organization->reporting_org[0]['reporting_organization_identifier'];
            $registrationSeparator = strrpos($orgIdentifier, '-');
            $registrationNumber    = substr($orgIdentifier, $registrationSeparator + 1);
            $registrationAgency    = substr($orgIdentifier, 0, $registrationSeparator);
            $country               = substr($orgIdentifier, 0, strpos($orgIdentifier, '-'));
        }
    }

    protected function orgName()
    {
        dd('orgName');
    }
}
