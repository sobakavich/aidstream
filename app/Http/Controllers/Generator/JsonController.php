<?php

namespace App\Http\Controllers\Generator;

use App\Http\Controllers\Controller;
use App\Services\Generator\Json;

/**
 * return All field and data;
 * @param string $table_name
 * @param int    $id
 * @return json $data
 */
class JsonController extends Controller
{

    protected $json;

    public function __construct(Json $json)
    {
        $this->json = $json;
    }

    public function index($table)
    {
        return $this->json->generatefield($table);

    }

    public function showData($table, $id)
    {
        return $this->json->generateData($table, $id);
    }
}