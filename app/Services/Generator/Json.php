<?php namespace App\Services\Generator;

use Illuminate\Support\Facades\DB;

Class Json
{
    public function generateField($table)
    {
        $result = DB::select(
            "select * FROM information_schema.columns 
                  WHERE table_schema = 'public' 
                  AND table_name = :table",
            ['table' => $table]
        );

        foreach ($result as $item) {
            $json [$item->column_name] = $item->data_type;
        }

        return $json;
    }

    public function generateData($table, $id)
    {
        $data = DB::table($table)->where('id', $id)->first();

        foreach ($data as $item => $value) {
            $json [$item] = $value;
        }

        return $json;
    }
}