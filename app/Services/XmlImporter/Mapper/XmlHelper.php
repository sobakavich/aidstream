<?php namespace App\Services\XmlImporter\Mapper;


/**
 * Class XmlHelper
 * @package App\Services\XmlImporter\Mapper
 */
trait XmlHelper
{
    /**
     * Returns lat and long for location field.
     * @param $values
     * @return array
     */
    protected function latAndLong($values)
    {
        $narrative = $this->value($values, 'point');
        $data      = ['latitude' => '', 'longitude' => ''];
        foreach ($narrative as $latLong) {
            $narrative = $latLong['narrative'];
            if ($narrative != "") {
                $text = explode(" ", $latLong['narrative']);
                if (count($text) == 2) {
                    $data['latitude']  = $text[0];
                    $data['longitude'] = $text[1];
                }
            }
        }

        return $data;
    }

    /**
     * Filter the provided key and groups the values in array.
     * $values = data['value']
     * @param      $values
     * @param null $key
     * @return array
     */
    protected function filterValues($values, $key = null)
    {
        $index = 0;
        $data  = [[$key => '']];
        foreach ($values as $value) {
            if ($this->name($value['name']) == $key) {
                $data[$index][$key] = $this->value($value);
                $index ++;
            }
        }

        return $data;
    }

    /**
     *  Filter the provided key, Convert the provided template to array and groups the attributes.
     * @param       $values
     * @param null  $key
     * @param array $template
     * @return array
     */
    protected function filterAttributes($values, $key = null, array $template)
    {
        $index = 0;
        $data  = $this->templateToArray($template);

        foreach ($values as $value) {
            if ($this->name($value['name']) == $key) {
                foreach ($value['attributes'] as $attributeKey => $attribute) {
                    $data[$index][$attributeKey] = $attribute;
                }
                $index ++;
            }
        }

        return $data;
    }

    /**
     * Converts the provided template into key empty value pairs.
     * @param array $template
     * @return array
     */
    protected function templateToArray(array $template)
    {
        if (is_array($template)) {
            $data = [array_flip($template)];
            foreach ($data as $index => $values) {
                foreach ($values as $key => $value) {
                    $data[$index][$key] = "";
                }
            }

            return $data;
        }

        return [];
    }

    /**
     * Get the value from the array.
     * If key is provided then the value is fetched from the value field of the data.
     * If key is provided then the $fields = $data['value'] else $fields = $data.
     * If the value is array then narrative is returned else only the value is returned.
     * @param array $fields
     * @param null  $key
     * @return array|mixed|string
     */
    protected function value(array $fields, $key = null)
    {
        if (!$key) {
            return getVal($fields, ['value'], '');
        }
        foreach ($fields as $field) {
            if ($this->name($field['name']) == $key) {
                if (is_array($field['value'])) {
                    return $this->narrative($field);
                }

                return getVal($field, ['value'], '');
            }
        }

        return [['narrative' => '', 'language' => '']];
    }

    /**
     * Returns the all narrative present in the provided $subElement.
     * @param $subElement
     * @return mixed
     * @internal param $field
     */
    protected function narrative($subElement)
    {
        $field = [['narrative' => '', 'language' => '']];
        if (is_array(getVal((array) $subElement, ['value'], []))) {
            foreach (getVal((array) $subElement, ['value'], []) as $index => $value) {
                $field[$index] = [
                    'narrative' => getVal($value, ['value'], ''),
                    'language'  => $this->attributes($value, 'language')
                ];
            }

            return $field;
        } else {
            $field = [
                'narrative' => getVal($subElement, ['value'], ''),
                'language'  => $this->attributes($subElement, 'language')
            ];

            return $field;
        }
    }

    /**
     * @param      $element
     * @param bool $snakeCase
     * @return string
     */
    protected function name($element, $snakeCase = false)
    {
        if (is_array($element)) {
            $camelCaseString = camel_case(str_replace('{}', '', $element['name']));

            return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
        }

        $camelCaseString = camel_case(str_replace('{}', '', $element));

        return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
    }

    /**
     * Returns the attributes of the provided element.
     * If key is provided then the attribute equal to the key is returned.
     * If fieldName and key both are provided then the attributes inside value is returned.
     * @param array $element
     * @param null  $key
     * @param null  $fieldName
     * @return mixed|string
     */
    protected function attributes(array $element, $key = null, $fieldName = null)
    {
        if (!$key) {
            return getVal($element, ['attributes'], []);
        }

        if ($fieldName && $key) {
            $value = "";
            foreach ($element['value'] as $value) {
                if ($fieldName == $this->name($value['name'])) {
                    return $this->attributes($value, $key);
                } else {
                    $value = "";
                }
            }

            return $value;
        }

        $value = getVal($element, ['attributes'], []);

        if ($value) {
            if ($key == 'language') {
                foreach ($value as $key => $item) {
                    if ($key == $this->name($key)) {
                        return $item;
                    }
                }
//                $code = array_first(
//                    $value,
//                    function () {
//                        return true;
//                    }
//                );

            }

            return getVal($element, ['attributes', $key], '');
        }

        return '';
    }
}