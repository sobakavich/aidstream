<?php namespace App\Http\Controllers\CsvImporter;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Import\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class UploadController
 * @package App\Http\Controllers\Uploader
 */
class ImportController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var ImportService
     */
    protected $importService;

    /**
     * UploadController constructor.
     * @param ImportService   $importService
     * @param ActivityManager $activityManager
     */
    public function __construct(ImportService $importService, ActivityManager $activityManager)
    {
        $this->importService   = $importService;
        $this->activityManager = $activityManager;
    }

    /**
     * Upload a Transaction CSV and import the Transactions into the database.
     * @param         $id
     * @param Request $request
     * @return mixed
     */
    public function transaction($id, Request $request)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $file     = $request->file('transaction');
        $filename = $file->getClientOriginalName();

        if ($imported = $this->importService->import($activity, $file)) {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Transactions Csv file has been uploaded successfully. Please confirm the Transactions you want to import.']]];

            return redirect()->route('import.transaction.status', [$id, $filename])->withResponse($response);
        } elseif (!$imported) {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => 'The file could not be uploaded.']]];
        } else {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => $imported]]];
        }

        return redirect()->to(sprintf('/activity/%s/transaction', $id))->withResponse($response);
    }

    /**
     * Show the Transaction import status.
     * @param $id
     * @param $filename
     * @return mixed
     */
    public function confirmTransactionsImport($id, $filename)
    {
        $activity = $this->activityManager->getActivityData($id);

        return view('uploader.transaction.upload-status', compact('activity', 'id', 'filename', 'transactions'));
    }

    /**
     * Get the transaction row from the uploaded CSV for import confirmation.
     * @param Request $request
     * @return mixed
     */
    public function getUploadedTransactionRows(Request $request)
    {
        $filename     = $request->get('filename');
        $transactions = $this->importService->getUploadedTransactionRows($filename);
        $view         = view('uploader.transaction.status', compact('transactions', 'filename'));

        return $view->render();
    }

    /**
     * Save validated Transaction rows from the uploaded CSV into the database..
     * @param         $activityId
     * @param Request $request
     * @return mixed
     */
    public function saveValidTransactions($activityId, Request $request)
    {
        $activity = $this->activityManager->getActivityData($activityId);

        if (!$this->importService->saveValidatedTransactions($activity, $request->except('_token'))) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Transaction could not be imported.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Transaction successfully imported.']]];
        }

        return redirect()->route('activity.transaction.index', $activityId)->withResponse($response);
    }
}
