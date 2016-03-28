<?php namespace App\Core\tz\Forms;

use App\Core\tz\BaseForm;

/**
 * Class SettingsForm
 * @package App\Core\tz\Forms
 */
class SettingsForm extends BaseForm
{

    public function buildForm()
    {
        $this
            ->addCollection('reporting_organization_info', 'Settings\ReportingOrganizationInfoForm')
            ->addCollection('registry_info', 'Settings\RegistryInfoForm')
            ->addCollection('default_field_values', 'Settings\DefaultFieldValuesForm', '', [], null, 'tz')
            ->add(
                'Save',
                'submit',
                [
                    'attr' => ['class' => 'btn btn-submit btn-form']
                ]
            )->add(
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
