<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ImportResult;
use App\Services\FormCreator\Activity\ImportResult as FormCreator;
use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Activity\ImportResult as ImportResultRequest;

/**
 * Class ImportResultController
 * @package App\Http\Controllers\Complete\Activity
 */
class ImportResultController extends Controller
{
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var FormCreator
     */
    protected $formCreator;
    /**
     * @var ImportResult
     */
    protected $importResultManager;

    /**
     * @var mixed
     */
    protected $organizationId;

    /**
     * @param ImportResult        $importResultManager
     * @param OrganizationManager $organizationManager
     * @param FormCreator         $formCreator
     */
    public function __construct(ImportResult $importResultManager, OrganizationManager $organizationManager, FormCreator $formCreator)
    {
        $this->organizationId      = session('org_id');
        $this->organizationManager = $organizationManager;
        $this->formCreator         = $formCreator;
        $this->importResultManager = $importResultManager;
    }

    /**
     * display Import Result form
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($activityId)
    {
//        session()->forget('activities');
        $organization = $this->organizationManager->getOrganization($this->organizationId);
        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }
        $form = $this->formCreator->createForm($activityId);

        return view('Activity.result.import', compact('form', 'activityId'));
    }

    /**
     * display result list to be imported with validation messages
     * @param                     $activityId
     * @param ImportResultRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listResults($activityId, ImportResultRequest $request)
    {
        $organization = $this->organizationManager->getOrganization($this->organizationId);
        $this->authorize('add_activity', $organization);

        $file    = request()->file('result');
        $results = $this->importResultManager->getResults($file);

        if ($results === false) {
            return redirect()->route('import-result.index', [$activityId])->withResponse(
                ['type' => 'warning', 'code' => ['message', ['message' => 'Uploaded csv file doesn\'t match with any template listed below.']]]
            );
        } elseif (!$results) {
            return redirect()->route('import-result.index', [$activityId])->withResponse(['type' => 'warning', 'code' => ['message', ['message' => 'Couldn\'t find results to be imported.']]]);
        }

        return view('Result.list-results', compact('results'));
    }

    /**
     * import selected results
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function importResults($activityId)
    {
        $activities = request()->get('activities');

        if (!$activities) {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => 'Please select at least one activity.']]];

            return redirect()->back()->withResponse($response);
        } elseif (!$this->importResultManager->importResults($activities)) {
            $response = ['type' => 'danger', 'code' => ['activities_import_failed']];

            return redirect()->back()->withResponse($response);
        }

        $importedResults = $this->importResultManager->getImportedResults();
        $response        = ['type' => 'success', 'code' => [count($importedResults) > 1 ? 'activities_imported' : 'activity_imported', ['activities' => implode(', ', $importedResults)]]];
        session()->forget('activities');

        return redirect()->route('activity.index')->withResponse($response);
    }
}
