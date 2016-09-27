<?php namespace App\Http\Controllers\Complete\Activity\Import;

use App\Core\V201\Requests\Activity\ImportActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
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
     * Directory where the validated Csv data is written before import.
     */
    const CSV_DATA_STORAGE_PATH = 'csvImporter/tmp';

    /**
     * File in which the valida Csv data is written before import.
     */
    const VALID_CSV_FILE = 'valid.json';

    /**
     * File in which the invalid Csv data is written before import.
     */
    const INVALID_CSV_FILE = 'invalid.json';

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
     * Activity with Transactions Template file path.
     */
    const TRANSACTION_ACTIVITY_TEMPLATE_PATH = '/Services/CsvImporter/Templates/Activity/%s/transaction.csv';

    /**
     * Current User's id.
     * @var
     */
    protected $userId;

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
        $this->userId              = auth()->user()->id;
        $this->middleware('auth');
    }

    /**
     * Download the Activity Template.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadActivityTemplate(Request $request)
    {
        $path = ($request->get('type') == 'basic') ? self::BASIC_ACTIVITY_TEMPLATE_PATH : self::TRANSACTION_ACTIVITY_TEMPLATE_PATH;

        return response()->download(app_path(sprintf($path, session('version'))));
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activities(ImportActivity $request)
    {
        $file = $request->file('activity');

        if (true === ($status = $this->importManager->verifyHeaders($file))) {
            $this->importManager->startImport();

            $this->importManager->process($file);

            return redirect()->route('activity.import-status');
        }

        $response = ['type' => 'danger', 'code' => ['csv_header_mismatch', ['message' => $status]]];

        return redirect()->to('import-activity')->withResponse($response);
    }

    /**
     * Get the valid Csv data for import.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getValidData()
    {
        $filepath = $this->importManager->getFilePath(true);

        if (file_exists($filepath)) {
            $activities = json_decode(file_get_contents($filepath), true);
            $tempPath   = $this->importManager->getTemporaryFilepath('valid-temp.json');

            file_put_contents($tempPath, json_encode($activities));

            $response = ['render' => view('Activity.csvImporter.valid', compact('activities'))->render()];
        } else {
            $response = ['render' => 'No data available.'];
        }

        return response()->json($response);
    }

    /**
     * Get the invalid Csv data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvalidData()
    {
        $filepath = $this->importManager->getFilePath(false);

        if (file_exists($filepath)) {
            $activities = json_decode(file_get_contents($filepath), true);
            $tempPath   = $this->importManager->getTemporaryFilepath('invalid-temp.json');


            file_put_contents($tempPath, json_encode($activities));

            $response = ['render' => view('Activity.csvImporter.invalid', compact('activities'))->render()];
        } else {
            $response = ['render' => 'No data available.'];
        }

        return response()->json($response);
    }

    /**
     * Import validated activities into the database.
     * @param Request $request
     * @return mixed
     */
    public function importValidatedActivities(Request $request)
    {
        $activities = $request->get('activities');

        if ($activities) {
            $contents = json_decode(file_get_contents($this->importManager->getFilePath(true)), true);

            $this->importManager->createActivity($activities, $contents);
            $this->importManager->endImport();

            return redirect()->route('activity.index')->withResponse(['type' => 'success', 'code' => ['message', ['message' => 'Activities successfully imported.']]]);
        } else {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => 'Please select the activities to be imported.']]]);
        }
    }

    /**
     * Show the status page for the Csv Import process.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status()
    {
        return view('Activity.csvImporter.status');
    }

    /**
     * Check Import Status.
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus()
    {
        $filePath = $this->importManager->getTemporaryFilepath('status.json');

        if (file_exists($filePath)) {
            return response()->json(file_get_contents($filePath));
        }

        return response()->json(json_encode(['status' => 'Incomplete']));
    }

    /**
     * Get the remaining invalid data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRemainingInvalidData()
    {
        $filepath = $this->importManager->getFilePath(false);

        if (file_exists($filepath)) {
            $activities = json_decode(file_get_contents($filepath), true);
            $tempPath   = $this->importManager->getTemporaryFilepath('invalid-temp.json');

            if (file_exists($tempPath)) {
                $old   = json_decode(file_get_contents($tempPath), true);
                $diff  = array_diff_key($activities, $old);
                $total = array_merge($diff, $old);

                file_put_contents($tempPath, json_encode($total));

                $activities = $diff;

                $response = ['render' => view('Activity.csvImporter.invalid', compact('activities'))->render()];

                return response()->json($response);
            } else {
                file_put_contents($tempPath, json_encode($activities));
            }

            $response = ['render' => view('Activity.csvImporter.invalid', compact('activities'))->render()];
        } else {
            $response = ['render' => 'No data available.'];
        }

        return response()->json($response);
    }

    /**
     * Get the remaining valid data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRemainingValidData()
    {
        $filepath = $this->importManager->getFilePath(true);

        if (file_exists($filepath)) {
            $activities = json_decode(file_get_contents($filepath), true);
            $tempPath   = $this->importManager->getTemporaryFilepath('valid-temp.json');

            if (file_exists($tempPath)) {
                $old   = json_decode(file_get_contents($tempPath), true);
                $diff  = array_diff_key($activities, $old);
                $total = array_merge($diff, $old);

                file_put_contents($tempPath, json_encode($total));

                $activities = $diff;

                $response = ['render' => view('Activity.csvImporter.valid', compact('activities'))->render()];

                return response()->json($response);
            } else {
                file_put_contents($tempPath, json_encode($activities));
            }

            $response = ['render' => view('Activity.csvImporter.valid', compact('activities'))->render()];
        } else {
            $response = ['render' => 'No data available.'];
        }

        return response()->json($response);
    }

    /**
     * Clear all invalid Activities.
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearInvalidActivities()
    {
        if ($this->importManager->clearInvalidActivities()) {
            return response()->json('cleared');
        }

        return response()->json('error');
    }
}
