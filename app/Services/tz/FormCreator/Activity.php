<?php namespace App\Services\tz\FormCreator;

use Kris\LaravelFormBuilder\FormBuilder;

class Activity
{
    protected $formBuilder;
    protected $formPath;

    /**
     * @param FormBuilder $formBuilder
     */
    function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = 'App\Core\tz\Forms\Activities';
    }

    /**
     * @return $this
     * return activity activity create form.
     */
    public function create()
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => route('activity.tzstore')
            ]
        )->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']])
                                 ->add(
                                     'Cancel',
                                     'static',
                                     [
                                         'tag'     => 'a',
                                         'label'   => false,
                                         'value'   => 'Cancel',
                                         'attr'    => [
                                             'class' => 'btn btn-cancel',
                                             'href'  => route('activity.index')
                                         ],
                                         'wrapper' => false
                                     ]
                                 );
    }

    /**
     * @param $activity
     * @return $this return activity activity edit form.
     * return activity activity edit form.
     */
    public function edit($activity, $id)
    {
        $model['activity'][0] = $activity;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('activity.tzupdate', $id)
            ]
        )
                                 ->add('id', 'hidden', ['value' => $id])
                                 ->add('Save', 'submit', ['attr' => ['class' => 'btn btn-submit btn-form']])
                                 ->add(
                                     'Cancel',
                                     'static',
                                     [
                                         'tag'     => 'a',
                                         'label'   => false,
                                         'value'   => 'Cancel',
                                         'attr'    => [
                                             'class' => 'btn btn-cancel',
                                             'href'  => route('activity.index')
                                         ],
                                         'wrapper' => false
                                     ]
                                 );
    }
}
