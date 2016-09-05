<?php namespace App\Http\Controllers\Complete\Activity\Import;

use App\Core\V201\Requests\Activity\ImportActivity;
use App\Http\Controllers\Controller;
use App\Services\CsvImporter\ImportManager;
use App\Services\FormCreator\Activity\ImportActivity as ImportActivityForm;
use App\Services\Organization\OrganizationManager;

/**
 * Class ImportController
 * @package App\Http\Controllers\Complete\Activity\Import
 */
class ImportController extends Controller
{
    /**
     * @var ImportActivityForm
     */
    protected $form;

    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @var ImportManager
     */
    protected $importManager;

    /**
     * Basic Activity Template file path.
     */
    const BASIC_ACTIVITY_TEMPLATE_PATH = '/Services/CsvImporter/Templates/Activity/%s/basic.csv';

    /**
     * ImportController constructor.
     * @param ImportActivityForm  $form
     * @param OrganizationManager $organizationManager
     * @param ImportManager       $importManager
     */
    public function __construct(ImportActivityForm $form, OrganizationManager $organizationManager, ImportManager $importManager)
    {
        $this->form                = $form;
        $this->organizationManager = $organizationManager;
        $this->importManager       = $importManager;
        $this->middleware('auth');
    }

    /**
     * Download the Activity Template.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadActivityTemplate()
    {
        return response()->download(app_path(sprintf(self::BASIC_ACTIVITY_TEMPLATE_PATH, session('version'))));
    }

    /**
     * Show the form to upload the Activity Csv.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadActivityCsv()
    {
        $organization = $this->organizationManager->getOrganization(session('org_id'));

        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $form = $this->form->createForm();

        return view('Activity.uploader', compact('form'));
    }

    /**
     * Import Activities into the database.
     * @param ImportActivity $request
     */
    public function activities(ImportActivity $request)
    {
        $file = $request->file('activity');

        $this->importManager->process($file);
    }
}
