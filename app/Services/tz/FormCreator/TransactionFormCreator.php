<?php namespace App\Services\tz\FormCreator;

use Kris\LaravelFormBuilder\FormBuilder;
use URL;


/**
 * Class TransactionFormCreator
 * @package App\Services\tz\FormCreator
 */
class TransactionFormCreator
{

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var string
     */
    protected $formPath;


    /**
     * TransactionFormCreator constructor.
     * @param FormBuilder $formBuilder
     */
    function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = 'App\Core\tz\Forms\Transactions';
    }


    /**
     * @param $activityId
     * @return $this
     */
    public function createForm($activityId, $code)
    {
        return $this->displayForm('POST', sprintf('activity/%d/transaction/%d', $activityId, $code), null, $activityId);
    }

    /**
     * @param $transactionDetails
     * @param $activityId
     * @return $this
     */
    public function editForm($transactionDetails, $activityId, $code)
    {
        return $this->displayForm('PUT', sprintf('activity/%d/transaction/%d', $activityId, $code), $transactionDetails, $activityId);
    }

    /**
     * @param      $method
     * @param      $url
     * @param null $data
     * @param null $activityId
     * @return $this
     */
    public function displayForm($method, $url, $data = null, $activityId = null)
    {
        $model['transaction'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => $method,
                'model'  => $model,
                'url'    => $url
            ]
        )->add((null !== $data) ? 'Update' : 'Create', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']])
                                 ->add(
                                     'Cancel',
                                     'static',
                                     [
                                         'tag'     => 'a',
                                         'label'   => false,
                                         'value'   => 'Cancel',
                                         'attr'    => [
                                             'class' => 'btn btn-cancel',
                                             'href'  => route('activity.transaction.index', $activityId)
                                         ],
                                         'wrapper' => false
                                     ]
                                 );
    }
}
