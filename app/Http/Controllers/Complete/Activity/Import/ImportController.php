<?php namespace App\Http\Controllers\Complete\Activity\Import;

use App\Core\V201\Requests\Activity\ImportActivity;
use App\Http\Controllers\Controller;
use App\Services\CsvImporter\ImportManager;
use App\Services\FormCreator\Activity\ImportActivity as ImportActivityForm;
use App\Services\Organization\OrganizationManager;
use Illuminate\Http\Request;

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
    protected $indices;

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
        $this->indices             = [];
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activities(ImportActivity $request)
    {
        $file = $request->file('activity');

        $this->importManager->startImport();

        $this->importManager->process($file);

        return redirect()->route('activity.import-status');
    }

    /**
     * Get the valid Csv data for import.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getValidData()
    {
        if (session()->get('import-status') == 'Complete') {
            $response = json_encode(['transferComplete' => true]);
            session()->forget('import-status');

            return response()->json($response);
        }

        $filepath = $this->getFilePath(false);
        $read     = [];

        if (file_exists($filepath)) {
            $activities = json_decode(file_get_contents($filepath), true);
            $indices    = array_keys($activities);

            foreach ($activities as $key => $activity) {
                $activity['status'] = 'read';
                $read[]             = $activity;
            }

            file_put_contents($filepath, json_encode($read));

            $response = ['render' => view('Activity.csvImporter.valid', compact('activities'))->render(), 'indices' => json_encode(array_keys($activities))];
        } else {
            $response = ['render' => 'No data available.', 'indices' => null];
        }

        return response()->json($response);
    }

    /**
     * Get the invalid Csv data.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvalidData()
    {
        if (session()->get('import-status') == 'Complete') {
            $response = json_encode(['transferComplete' => true]);

            return response()->json($response);
        }

        $filepath = $this->getFilePath(false);

        if (file_exists($filepath)) {
            $activities      = json_decode(file_get_contents($filepath), true);
            $keys            = array_keys($activities);

//            array_walk($keys, function ($index, $value) {
//                dd($index, $value);
////                $this->indices[] = $value;
//            });


            $response = ['render' => view('Activity.csvImporter.invalid', compact('activities'))->render(), 'indices' => json_encode($keys)];
        } else {
            $response = ['render' => 'No data available.', 'indices' => null];
        }

        return response()->json($response);
    }

    /**
     * Get the filepath to the file in which the Csv data is written after processing for import.
     * @param $isValid
     * @return string
     */
    protected function getFilePath($isValid)
    {
        if ($isValid) {
            return storage_path(sprintf('%s/%s', self::CSV_DATA_STORAGE_PATH, self::VALID_CSV_FILE));
        }

        return storage_path(sprintf('%s/%s', self::CSV_DATA_STORAGE_PATH, self::INVALID_CSV_FILE));
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
            $contents = json_decode(file_get_contents($this->importManager->getFilePath(false)), true);

            $this->importManager->createActivity($activities, $contents);
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
}
