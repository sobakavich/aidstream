<?php

use Illuminate\Database\DatabaseManager;

function getOrganizationFor($accountId)
{
    return app()->make(DatabaseManager::class)
                ->connection('mysql')
                ->table('iati_organisation')
                ->select('id')
                ->where('account_id', '=', $accountId)
                ->first();
}
