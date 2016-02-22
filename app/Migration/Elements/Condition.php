<?php namespace App\Migration\Elements;


class Condition
{
    public function format($attached, $condition)
    {
        return [
            'condition_attached' => $attached,
            'condition'          => $condition ? $condition : ""
        ];
    }
}
