<?php namespace App\Http\Controllers\tz;

use App\Core\tz\Requests\TransactionRequest;
use App\Services\Activity\ActivityManager;
use App\Services\tz\FormCreator\TransactionFormCreator as FormBuilder;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\tz\Manager\TransactionManager;


/**
 * Class TransactionController
 * @package App\Http\Controllers\tz
 */
class TransactionController extends Controller
{
    /**
     * @var TransactionManager
     */
    protected $transactionManager;

    /**
     * @var
     */
    protected $activityManager;


    /**
     * TransactionController constructor.
     * @param TransactionManager $transactionManager
     * @param ActivityManager    $activityManager
     */
    function __construct(TransactionManager $transactionManager, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->transactionManager = $transactionManager;
        $this->activityManager    = $activityManager;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        $incomingFund = $this->transactionManager->getTransactions($id, 1);
        $commitment   = $this->transactionManager->getTransactions($id, 2);
        $disbursement = $this->transactionManager->getTransactions($id, 3);
        $expenditure  = $this->transactionManager->getTransactions($id, 4);

        return view('tz.Transaction.list', compact('incomingFund', 'commitment', 'disbursement', 'expenditure', 'activity', 'id'));
    }

    /**
     * @param FormBuilder $formBuilder
     * @param             $id
     * @param             $code
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(FormBuilder $formBuilder, $id, $code)
    {
        $form = $formBuilder->createForm($id, $code);

        return view('tz.Transaction.create', compact('form'));
    }


    /**
     * @param TransactionRequest $request
     * @param                    $activityId
     * @param                    $code
     * @return
     */
    public function store(TransactionRequest $request, $activityId, $code)
    {
        $this->authorize('add_activity');

        $transactions = $request->get('transaction');
        $activity     = $this->activityManager->getActivityData($activityId);
        $this->transactionManager->saveTransactions($transactions, $activity, $code);
        $this->activityManager->resetActivityWorkflow($activityId);

        $response = ['type' => 'success', 'code' => ['saved', ['name' => 'Transaction(s)']]];

        return redirect()->to(sprintf('/activity/%s/transaction', $activityId))->withResponse($response);
    }

    /**
     * @param FormBuilder $formBuilder
     * @param             $id
     * @param             $code
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(FormBuilder $formBuilder, $id, $code)
    {
        $this->authorize('edit_activity');

        $transaction = $this->transactionManager->getTransactions($id, $code);
        $activity    = $this->activityManager->getActivityData($id);

        $form = $formBuilder->editForm($transaction, $id, $code);

        return view('tz.Transaction.create', compact('form', 'activity', 'id'));
    }

    public function destroy($id, $transactionId)
    {
        $this->authorize('delete_activity');

        $transaction = $this->transactionManager->getTransaction($transactionId);
        $response    = ($transaction->delete($transaction)) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'Transaction']]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => 'transaction']]
        ];

        return redirect()->back()->withResponse($response);
    }

}
