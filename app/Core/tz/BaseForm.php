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
}
