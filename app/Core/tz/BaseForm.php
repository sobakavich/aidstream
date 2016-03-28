<?php namespace App\Core\tz;

use App\Core\Form\BaseForm as Form;

/**
 * Class BaseForm
 * @package App\Core\tz
 */
class BaseForm extends Form
{

    /**
     * @param        $name
     * @param        $file
     * @param string $class
     * @param array  $data
     * @param null   $label
     * @param null   $basePath
     * @return $this
     */
    protected function addCollection($name, $file, $class = "", array $data = [], $label = null, $basePath = null)
    {
        $class .= ($class ? ' has_add_more' : '');
        $defaultVersion = config('app.default_version_name');
        $filePath       = '';
        if ($basePath) {
            $filePath = sprintf('App\Core\%s\Forms\%s', $basePath, $file);
            class_exists($filePath) ?: $filePath = '';
        }
        $filePath ?: $filePath = sprintf('App\Core\%s\Forms\%s', session()->get('version'), $file);
        $FormClass = !class_exists($filePath) ? sprintf('App\Core\%s\Forms\%s', $defaultVersion, $file) : $filePath;

        return $this->add(
            $name,
            'collection',
            [
                'type'    => 'form',
                'options' => [
                    'class' => $FormClass,
                    'data'  => $data,
                    'label' => false,
                ],
                'label'   => $label,
                'wrapper' => [
                    'class' => sprintf('collection_form %s', $class)
                ]
            ]
        );
    }

    /**
     * @param       $name
     * @param array $choices
     * @param null  $label
     * @param null  $helpText
     * @param null  $defaultValue
     * @param bool  $required
     * @return $this
     */
    protected function addMultipleSelect($name, array $choices, $label = null, $helpText = null, $defaultValue = null, $required = false)
    {
        return $this->add(
            $name,
            'choice',
            [
                'choices'       => $choices,
                'label'         => $label,
                'default_value' => $defaultValue,
                'help_block'    => $helpText,
                'required'      => $required,
                'multiple'      => true,
                'wrapper'       => ['class' => 'form-group multi-select']
            ]
        );
    }

    /**
     * @param       $file
     * @param array $options
     * @param null  $basePath
     * @return $this
     */
    protected function addForm($file, $options = [], $basePath = null)
    {
        $defaultVersion = config('app.default_version_name');
        $filePath       = '';
        if ($basePath) {
            $filePath = sprintf('App\Core\%s\Forms\%s', $basePath, $file);
            class_exists($filePath) ?: $filePath = '';
        }
        $filePath ?: $filePath = sprintf('App\Core\%s\Forms\%s', session()->get('version'), $file);
        $formClass = !class_exists($filePath) ? sprintf('App\Core\%s\Forms\%s', $defaultVersion, $file) : $filePath;

        return $this->compose($formClass, $options);
    }
}
