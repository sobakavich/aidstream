<?php

namespace App\Http\Controllers\Generator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * return All field and data;
 * @param string $table_name
 * @param int    $id
 * @return json data
 */
class JsonController extends Controller
{

    protected $json;

    public function index($table)
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
}