<?php namespace App\Services\tz\FormCreator;

use Kris\LaravelFormBuilder\FormBuilder;
use URL;

/**
 * Class SettingsFormCreator
 * @package App\Services\tz\FormCreator
 */
class SettingsFormCreator
{

    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    protected $formPath;


    /**
     * SettingsFormCreator constructor.
     * @param FormBuilder $formBuilder
     */
    function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
        $this->formPath    = 'App\Core\tz\Forms\SettingsForm';
    }

    /**
     * @param $model
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function editForm($model)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $model,
                'url'    => route('settings.update', [0])
            ]
        );
    }
}
